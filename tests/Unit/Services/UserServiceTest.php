<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;
    private User $admin;
    private User $approver;
    private User $requester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();

        // Criar usuários para teste
        $this->admin = User::factory()->create(['profile' => 'admin']);
        $this->approver = User::factory()->create(['profile' => 'approver']);
        $this->requester = User::factory()->create(['profile' => 'requester']);
    }

    /** @test */
    public function apenas_admin_pode_criar_usuario()
    {
        $dados = [
            'name' => 'João Silva',
            'email' => 'joao.silva@exemplo.com',
            'password' => 'senha@123',
            'profile' => 'requester'
        ];

        // Tenta criar com usuário não admin
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem criar usuários.');
        $this->userService->create($dados, $this->requester);
    }

    /** @test */
    public function cria_usuario_com_sucesso()
    {
        $dados = [
            'name' => 'João Silva',
            'email' => 'joao.silva@exemplo.com',
            'password' => 'senha@123',
            'profile' => 'requester'
        ];

        $novoUsuario = $this->userService->create($dados, $this->admin);

        $this->assertInstanceOf(User::class, $novoUsuario);
        $this->assertEquals($dados['name'], $novoUsuario->name);
        $this->assertEquals($dados['email'], $novoUsuario->email);
        $this->assertEquals($dados['profile'], $novoUsuario->profile);
        $this->assertTrue(Hash::check($dados['password'], $novoUsuario->password));
    }

    /** @test */
    public function apenas_admin_pode_atualizar_usuario()
    {
        $usuario = User::factory()->create();
        $dados = [
            'name' => 'Nome Atualizado',
            'email' => 'email.atualizado@exemplo.com',
            'profile' => 'requester'
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem atualizar usuários.');
        $this->userService->update($usuario, $dados, $this->requester);
    }

    /** @test */
    public function atualiza_usuario_com_sucesso()
    {
        $usuario = User::factory()->create();
        $dados = [
            'name' => 'Nome Atualizado',
            'email' => 'email.atualizado@exemplo.com',
            'profile' => 'requester',
            'password' => 'nova_senha@123'
        ];

        $usuarioAtualizado = $this->userService->update($usuario, $dados, $this->admin);

        $this->assertEquals($dados['name'], $usuarioAtualizado->name);
        $this->assertEquals($dados['email'], $usuarioAtualizado->email);
        $this->assertEquals($dados['profile'], $usuarioAtualizado->profile);
        $this->assertTrue(Hash::check($dados['password'], $usuarioAtualizado->password));
    }

    /** @test */
    public function apenas_admin_pode_deletar_usuario()
    {
        $usuario = User::factory()->create();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas administradores podem excluir usuários.');
        $this->userService->delete($usuario, $this->requester);
    }

    /** @test */
    public function nao_pode_deletar_proprio_usuario()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Você não pode excluir seu próprio usuário.');
        $this->userService->delete($this->admin, $this->admin);
    }

    /** @test */
    public function deleta_usuario_com_sucesso()
    {
        $usuario = User::factory()->create();

        $this->userService->delete($usuario, $this->admin);

        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
    }

    /** @test */
    public function lista_usuarios_com_filtros()
    {
        // Criar alguns usuários para teste
        User::factory()->count(3)->create(['profile' => 'requester']);
        User::factory()->create(['name' => 'Teste Busca', 'email' => 'teste@busca.com']);

        // Testar listagem com filtro de busca
        $resultadoBusca = $this->userService->list(['search' => 'Teste Busca']);
        $this->assertCount(1, $resultadoBusca);
        $this->assertEquals('Teste Busca', $resultadoBusca->first()->name);

        // Testar listagem com filtro de perfil
        $resultadoPerfil = $this->userService->list(['profile' => 'requester']);
        $this->assertEquals(4, $resultadoPerfil->count()); // 3 criados + 1 do setUp

        // Testar ordenação
        $resultadoOrdenado = $this->userService->list([], 'email', 'desc');
        $this->assertEquals('teste@busca.com', $resultadoOrdenado->first()->email);
    }

    /** @test */
    public function verifica_email_unico()
    {
        $usuario = User::factory()->create(['email' => 'teste@exemplo.com']);

        // Email já existe
        $this->assertFalse($this->userService->isEmailUnique('teste@exemplo.com'));

        // Email existe mas é do próprio usuário
        $this->assertTrue($this->userService->isEmailUnique('teste@exemplo.com', $usuario->id));

        // Email não existe
        $this->assertTrue($this->userService->isEmailUnique('novo@exemplo.com'));
    }

    /** @test */
    public function retorna_perfis_disponiveis()
    {
        $perfis = $this->userService->getAvailableProfiles();

        $this->assertIsArray($perfis);
        $this->assertArrayHasKey('admin', $perfis);
        $this->assertArrayHasKey('approver', $perfis);
        $this->assertArrayHasKey('requester', $perfis);
        $this->assertEquals('Administrador', $perfis['admin']);
        $this->assertEquals('Aprovador', $perfis['approver']);
        $this->assertEquals('Solicitante', $perfis['requester']);
    }
}
