<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Material;
use App\Models\Order;
use App\Services\MaterialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class MaterialServiceTest extends TestCase
{
    use RefreshDatabase;

    private MaterialService $materialService;
    private User $admin;
    private User $approver;
    private User $requester;
    private Material $material;

    protected function setUp(): void
    {
        parent::setUp();
        $this->materialService = new MaterialService();

        // Criar usuários para teste
        $this->admin = User::factory()->create(['profile' => 'admin']);
        $this->approver = User::factory()->create(['profile' => 'approver']);
        $this->requester = User::factory()->create(['profile' => 'requester']);

        // Criar um material para teste
        $this->material = Material::create([
            'name' => 'Material Teste',
            'price' => 10.00
        ]);
    }

    /** @test */
    public function lista_todos_materiais()
    {
        // Criar mais alguns materiais
        Material::create(['name' => 'Material 2', 'price' => 20.00]);
        Material::create(['name' => 'Material 3', 'price' => 30.00]);

        $materiais = $this->materialService->list();

        $this->assertEquals(3, $materiais->count());
        $this->assertEquals('Material 2', $materiais[1]->name);
    }

    /** @test */
    public function apenas_admin_pode_criar_material()
    {
        $dados = [
            'name' => 'Novo Material',
            'price' => 15.00
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem criar materiais.');
        $this->materialService->create($dados, $this->requester);
    }

    /** @test */
    public function nao_permite_criar_material_com_preco_zero_ou_negativo()
    {
        $dados = [
            'name' => 'Novo Material',
            'price' => 0
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O preço do material deve ser maior que zero.');
        $this->materialService->create($dados, $this->admin);
    }

    /** @test */
    public function cria_material_com_sucesso()
    {
        $dados = [
            'name' => 'Novo Material',
            'price' => 15.00
        ];

        $novoMaterial = $this->materialService->create($dados, $this->admin);

        $this->assertInstanceOf(Material::class, $novoMaterial);
        $this->assertEquals($dados['name'], $novoMaterial->name);
        $this->assertEquals($dados['price'], $novoMaterial->price);
    }

    /** @test */
    public function apenas_admin_pode_atualizar_material()
    {
        $dados = [
            'name' => 'Material Atualizado',
            'price' => 25.00
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem atualizar materiais.');
        $this->materialService->update($this->material, $dados, $this->requester);
    }

    /** @test */
    public function nao_permite_atualizar_material_com_preco_zero_ou_negativo()
    {
        $dados = [
            'name' => 'Material Atualizado',
            'price' => -10.00
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O preço do material deve ser maior que zero.');
        $this->materialService->update($this->material, $dados, $this->admin);
    }

    /** @test */
    public function atualiza_material_com_sucesso()
    {
        $dados = [
            'name' => 'Material Atualizado',
            'price' => 25.00
        ];

        $materialAtualizado = $this->materialService->update($this->material, $dados, $this->admin);

        $this->assertEquals($dados['name'], $materialAtualizado->name);
        $this->assertEquals($dados['price'], $materialAtualizado->price);
    }

    /** @test */
    public function apenas_admin_pode_deletar_material()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem excluir materiais.');
        $this->materialService->delete($this->material, $this->requester);
    }

    /** @test */
    public function nao_permite_deletar_material_em_uso()
    {
        // Criar um pedido usando o material
        $order = Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => 1,
            'total' => 100.00,
            'status' => 'approved'
        ]);

        $order->materials()->attach($this->material->id, [
            'quantity' => 1,
            'subtotal' => 10.00
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Não é possível excluir um material que já foi usado em pedidos.');
        $this->materialService->delete($this->material, $this->admin);
    }

    /** @test */
    public function deleta_material_com_sucesso()
    {
        $this->materialService->delete($this->material, $this->admin);
        $this->assertDatabaseMissing('materials', ['id' => $this->material->id]);
    }

    /** @test */
    public function busca_materiais_por_nome()
    {
        Material::create(['name' => 'Caneta Azul', 'price' => 2.50]);
        Material::create(['name' => 'Caneta Vermelha', 'price' => 2.50]);
        Material::create(['name' => 'Lápis', 'price' => 1.50]);

        $resultados = $this->materialService->search('Caneta');

        $this->assertEquals(2, $resultados->count());
        $this->assertTrue($resultados->pluck('name')->contains('Caneta Azul'));
        $this->assertTrue($resultados->pluck('name')->contains('Caneta Vermelha'));
    }

    /** @test */
    public function gera_relatorio_de_uso()
    {
        // Criar pedidos usando o material
        $order1 = Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => 1,
            'total' => 20.00,
            'status' => 'approved',
            'created_date' => '2024-01-01'
        ]);

        $order2 = Order::create([
            'requester_id' => $this->requester->id,
            'group_id' => 1,
            'total' => 30.00,
            'status' => 'approved',
            'created_date' => '2024-01-15'
        ]);

        $order1->materials()->attach($this->material->id, [
            'quantity' => 1,
            'subtotal' => 10.00
        ]);

        $order2->materials()->attach($this->material->id, [
            'quantity' => 2,
            'subtotal' => 20.00
        ]);

        $relatorio = $this->materialService->getUsageReport('2024-01-01', '2024-01-31');

        $this->assertCount(1, $relatorio);
        $materialReport = $relatorio[0];
        $this->assertEquals($this->material->id, $materialReport['material_id']);
        $this->assertEquals(3, $materialReport['total_quantity']); // 1 + 2
        $this->assertEquals(30.00, $materialReport['total_value']); // 10 + 20
        $this->assertEquals(10.00, $materialReport['average_price']); // 30 / 3
    }
}
