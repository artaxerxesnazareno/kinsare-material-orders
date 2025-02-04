<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Order;
use App\Models\Material;
use App\Models\Requester;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $orderService;
    private User $admin;
    private User $approver;
    private User $requester;
    private Group $group;
    private Material $material;
    private Order $order;
    private Requester $requesterModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();

        // Criar usuários para teste
        $this->admin = User::factory()->create(['profile' => 'admin']);
        $this->approver = User::factory()->create(['profile' => 'approver']);
        $this->requester = User::factory()->create(['profile' => 'requester']);

        // Criar grupo
        $this->group = Group::create([
            'name' => 'Grupo Teste',
            'allowed_balance' => 5000.00,
            'approver_id' => $this->approver->id
        ]);

        // Criar material
        $this->material = Material::create([
            'name' => 'Material Teste',
            'price' => 100.00
        ]);

        // Criar requester
        $this->requesterModel = Requester::create([
            'user_id' => $this->requester->id,
            'group_id' => $this->group->id
        ]);

        // Criar pedido
        $this->order = Order::create([
            'requester_id' => $this->requesterModel->id,
            'group_id' => $this->group->id,
            'total' => 200.00,
            'status' => 'new'
        ]);

        $this->order->materials()->attach($this->material->id, [
            'quantity' => 2,
            'subtotal' => 200.00
        ]);
    }

    /** @test */
    public function apenas_solicitante_pode_criar_pedido()
    {
        $dados = [
            'materials' => [
                ['id' => $this->material->id, 'quantity' => 1]
            ]
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas solicitantes podem criar pedidos.');
        $this->orderService->create($dados, $this->admin);
    }

    /** @test */
    public function cria_pedido_com_sucesso()
    {
        $dados = [
            'materials' => [
                ['id' => $this->material->id, 'quantity' => 2]
            ]
        ];

        $novoPedido = $this->orderService->create($dados, $this->requester);

        $this->assertInstanceOf(Order::class, $novoPedido);
        $this->assertEquals($this->requesterModel->id, $novoPedido->requester_id);
        $this->assertEquals($this->group->id, $novoPedido->group_id);
        $this->assertEquals('new', $novoPedido->status);
        $this->assertEquals(200.00, $novoPedido->total); // 2 * 100.00
    }

    /** @test */
    public function apenas_aprovador_pode_aprovar_pedido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas aprovadores podem aprovar pedidos.');
        $this->orderService->approve($this->order, $this->requester);
    }

    /** @test */
    public function nao_aprova_pedido_com_status_invalido()
    {
        $this->order->update(['status' => 'approved']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas pedidos novos ou em revisão podem ser aprovados.');
        $this->orderService->approve($this->order, $this->approver);
    }

    /** @test */
    public function nao_aprova_pedido_sem_saldo()
    {
        $this->group->update(['allowed_balance' => 100.00]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Saldo insuficiente no grupo para aprovar este pedido.');
        $this->orderService->approve($this->order, $this->approver);
    }

    /** @test */
    public function aprova_pedido_com_sucesso()
    {
        $pedidoAprovado = $this->orderService->approve($this->order, $this->approver);

        $this->assertEquals('approved', $pedidoAprovado->status);
    }

    /** @test */
    public function apenas_aprovador_pode_rejeitar_pedido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas aprovadores podem rejeitar pedidos.');
        $this->orderService->reject($this->order, $this->requester, 'Motivo da rejeição');
    }

    /** @test */
    public function nao_rejeita_pedido_com_status_invalido()
    {
        $this->order->update(['status' => 'rejected']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas pedidos novos ou em revisão podem ser rejeitados.');
        $this->orderService->reject($this->order, $this->approver, 'Motivo da rejeição');
    }

    /** @test */
    public function rejeita_pedido_com_sucesso()
    {
        $motivo = 'Pedido fora do padrão';
        $pedidoRejeitado = $this->orderService->reject($this->order, $this->approver, $motivo);

        $this->assertEquals('rejected', $pedidoRejeitado->status);
        $this->assertEquals($motivo, $pedidoRejeitado->rejection_reason);
    }

    /** @test */
    public function apenas_aprovador_pode_solicitar_alteracoes()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas aprovadores podem solicitar alterações em pedidos.');
        $this->orderService->requestChanges($this->order, $this->requester, 'Alterações necessárias');
    }

    /** @test */
    public function nao_solicita_alteracoes_em_pedido_com_status_invalido()
    {
        $this->order->update(['status' => 'approved']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas pedidos novos ou em revisão podem receber solicitações de alteração.');
        $this->orderService->requestChanges($this->order, $this->approver, 'Alterações necessárias');
    }

    /** @test */
    public function solicita_alteracoes_com_sucesso()
    {
        $alteracoes = 'Revisar quantidades';
        $pedidoAlterado = $this->orderService->requestChanges($this->order, $this->approver, $alteracoes);

        $this->assertEquals('changes_requested', $pedidoAlterado->status);
        $this->assertEquals($alteracoes, $pedidoAlterado->change_request);
    }

    /** @test */
    public function apenas_solicitante_pode_enviar_para_revisao()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas solicitantes podem enviar pedidos para revisão.');
        $this->orderService->sendToReview($this->order, $this->admin);
    }

    /** @test */
    public function nao_envia_para_revisao_pedido_com_status_invalido()
    {
        $this->order->update(['status' => 'approved']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas pedidos novos ou com alterações solicitadas podem ser enviados para revisão.');
        $this->orderService->sendToReview($this->order, $this->requester);
    }

    /** @test */
    public function envia_para_revisao_com_sucesso()
    {
        $pedidoEmRevisao = $this->orderService->sendToReview($this->order, $this->requester);

        $this->assertEquals('in_review', $pedidoEmRevisao->status);
    }

    /** @test */
    public function lista_pedidos_para_admin()
    {
        $pedidos = $this->orderService->list($this->admin);

        $this->assertEquals(1, $pedidos->count());
        $this->assertEquals($this->order->id, $pedidos->first()->id);
    }

    /** @test */
    public function lista_pedidos_para_aprovador()
    {
        // Criar outro grupo com outro aprovador
        $outroGrupo = Group::create([
            'name' => 'Outro Grupo',
            'allowed_balance' => 1000.00,
            'approver_id' => User::factory()->create(['profile' => 'approver'])->id
        ]);

        Order::create([
            'requester_id' => $this->requesterModel->id,
            'group_id' => $outroGrupo->id,
            'total' => 100.00,
            'status' => 'new'
        ]);

        $pedidos = $this->orderService->list($this->approver);

        $this->assertEquals(1, $pedidos->count());
        $this->assertEquals($this->order->id, $pedidos->first()->id);
    }

    /** @test */
    public function lista_pedidos_para_solicitante()
    {
        // Criar outro pedido de outro solicitante
        $outroSolicitante = User::factory()->create(['profile' => 'requester']);
        $outroRequester = Requester::create([
            'user_id' => $outroSolicitante->id,
            'group_id' => $this->group->id
        ]);

        Order::create([
            'requester_id' => $outroRequester->id,
            'group_id' => $this->group->id,
            'total' => 100.00,
            'status' => 'new'
        ]);

        $pedidos = $this->orderService->list($this->requester);

        $this->assertEquals(1, $pedidos->count());
        $this->assertEquals($this->order->id, $pedidos->first()->id);
    }
}
