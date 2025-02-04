<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Order;
use App\Models\Requester;
use App\Services\GroupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class GroupServiceTest extends TestCase
{
    use RefreshDatabase;

    private GroupService $groupService;
    private User $admin;
    private User $approver;
    private User $requester;
    private Group $group;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groupService = new GroupService();

        // Criar usuários para teste
        $this->admin = User::factory()->create(['profile' => 'admin']);
        $this->approver = User::factory()->create(['profile' => 'approver']);
        $this->requester = User::factory()->create(['profile' => 'requester']);

        // Criar um grupo para teste
        $this->group = Group::create([
            'name' => 'Grupo Teste',
            'allowed_balance' => 5000.00,
            'approver_id' => $this->approver->id
        ]);
    }

    /** @test */
    public function apenas_admin_pode_criar_grupo()
    {
        $dados = [
            'name' => 'Novo Grupo',
            'allowed_balance' => 1000.00,
            'approver_id' => $this->approver->id,
            'requester_id' => $this->requester->id
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem criar grupos.');
        $this->groupService->create($dados, $this->requester);
    }

    /** @test */
    public function cria_grupo_com_sucesso()
    {
        $dados = [
            'name' => 'Novo Grupo',
            'allowed_balance' => 1000.00,
            'approver_id' => $this->approver->id,
            'requester_id' => $this->requester->id
        ];

        $novoGrupo = $this->groupService->create($dados, $this->admin);

        $this->assertInstanceOf(Group::class, $novoGrupo);
        $this->assertEquals($dados['name'], $novoGrupo->name);
        $this->assertEquals($dados['allowed_balance'], $novoGrupo->allowed_balance);
        $this->assertEquals($dados['approver_id'], $novoGrupo->approver_id);

        // Verifica se o solicitante foi associado ao grupo
        $this->assertDatabaseHas('requesters', [
            'user_id' => $dados['requester_id'],
            'group_id' => $novoGrupo->id
        ]);
    }

    /** @test */
    public function verifica_saldo_disponivel()
    {
        // Criar pedido aprovado
        Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => $this->group->id,
            'total' => 2000.00,
            'status' => 'approved'
        ]);

        // Deve permitir um pedido de 2000 (saldo disponível = 3000)
        $this->assertTrue($this->groupService->checkAvailableBalance($this->group, 2000.00));

        // Não deve permitir um pedido de 4000 (excede o saldo disponível)
        $this->assertFalse($this->groupService->checkAvailableBalance($this->group, 4000.00));
    }

    /** @test */
    public function obtem_saldo_atual()
    {
        // Criar pedido aprovado
        Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => $this->group->id,
            'total' => 2000.00,
            'status' => 'approved'
        ]);

        $saldoAtual = $this->groupService->getCurrentBalance($this->group);

        // Saldo atual deve ser 3000 (5000 - 2000)
        $this->assertEquals(3000.00, $saldoAtual);
    }

    /** @test */
    public function lista_grupos_acessiveis_para_admin()
    {
        Group::create([
            'name' => 'Grupo 2',
            'allowed_balance' => 3000.00,
            'approver_id' => $this->approver->id
        ]);

        $grupos = $this->groupService->listAccessibleGroups($this->admin);

        // Admin deve ver todos os grupos
        $this->assertEquals(2, $grupos->count());
    }

    /** @test */
    public function lista_grupos_acessiveis_para_aprovador()
    {
        Group::create([
            'name' => 'Grupo Outro Aprovador',
            'allowed_balance' => 3000.00,
            'approver_id' => User::factory()->create(['profile' => 'approver'])->id
        ]);

        $grupos = $this->groupService->listAccessibleGroups($this->approver);

        // Aprovador deve ver apenas seus grupos
        $this->assertEquals(1, $grupos->count());
        $this->assertEquals($this->group->id, $grupos->first()->id);
    }

    /** @test */
    public function lista_grupos_acessiveis_para_solicitante()
    {
        // Associar solicitante ao grupo
        Requester::create([
            'user_id' => $this->requester->id,
            'group_id' => $this->group->id
        ]);

        $grupos = $this->groupService->listAccessibleGroups($this->requester);

        // Solicitante deve ver apenas seu grupo
        $this->assertEquals(1, $grupos->count());
        $this->assertEquals($this->group->id, $grupos->first()->id);
    }

    /** @test */
    public function apenas_admin_pode_atualizar_saldo_permitido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem alterar o saldo permitido dos grupos.');
        $this->groupService->updateAllowedBalance($this->group, 6000.00, $this->requester);
    }

    /** @test */
    public function nao_permite_saldo_negativo()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O saldo permitido não pode ser negativo.');
        $this->groupService->updateAllowedBalance($this->group, -1000.00, $this->admin);
    }

    /** @test */
    public function atualiza_saldo_permitido_com_sucesso()
    {
        $novoSaldo = 6000.00;
        $grupoAtualizado = $this->groupService->updateAllowedBalance($this->group, $novoSaldo, $this->admin);

        $this->assertEquals($novoSaldo, $grupoAtualizado->allowed_balance);
    }

    /** @test */
    public function gera_relatorio_de_gastos()
    {
        // Criar alguns pedidos aprovados
        Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => $this->group->id,
            'total' => 1000.00,
            'status' => 'approved',
            'created_date' => '2024-01-01'
        ]);

        Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => $this->group->id,
            'total' => 2000.00,
            'status' => 'approved',
            'created_date' => '2024-01-15'
        ]);

        $relatorio = $this->groupService->getSpendingReport(
            $this->group,
            '2024-01-01',
            '2024-01-31'
        );

        $this->assertEquals(3000.00, $relatorio['total_spent']);
        $this->assertEquals(2, $relatorio['order_count']);
        $this->assertEquals(1500.00, $relatorio['average_order_value']);
        $this->assertEquals(2000.00, $relatorio['remaining_balance']); // 5000 - 3000
    }

    /** @test */
    public function apenas_admin_pode_deletar_grupo()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem remover grupos.');
        $this->groupService->delete($this->group, $this->requester);
    }

    /** @test */
    public function deleta_grupo_com_sucesso()
    {
        // Associar solicitante ao grupo
        Requester::create([
            'user_id' => $this->requester->id,
            'group_id' => $this->group->id
        ]);

        $this->groupService->delete($this->group, $this->admin);

        // Verifica se o grupo foi deletado
        $this->assertDatabaseMissing('groups', ['id' => $this->group->id]);

        // Verifica se os relacionamentos foram removidos
        $this->assertDatabaseMissing('requesters', ['group_id' => $this->group->id]);
    }
}
