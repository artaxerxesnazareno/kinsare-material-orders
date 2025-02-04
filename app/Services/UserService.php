<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService
{
    /**
     * Criar um novo usuário
     * RNF1: Segurança - Apenas admin pode criar usuários
     */
    public function create(array $data, User $admin): User
    {
        if (!$admin->isAdmin()) {
            throw new Exception('Apenas administradores podem criar usuários.');
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'profile' => $data['profile']
        ]);
    }

    /**
     * Atualizar um usuário existente
     * RNF1: Segurança - Apenas admin pode atualizar usuários
     */
    public function update(User $user, array $data, User $admin): User
    {
        if (!$admin->isAdmin()) {
            throw new Exception('Apenas administradores podem atualizar usuários.');
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'profile' => $data['profile']
        ];

        // Atualiza a senha apenas se uma nova senha foi fornecida
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        return $user->fresh();
    }

    /**
     * Excluir um usuário
     * RNF1: Segurança - Apenas admin pode excluir usuários
     */
    public function delete(User $user, User $admin): void
    {
        if (!$admin->isAdmin()) {
            throw new Exception('Apenas administradores podem excluir usuários.');
        }

        if ($user->id === $admin->id) {
            throw new Exception('Você não pode excluir seu próprio usuário.');
        }

        $user->delete();
    }

    /**
     * Listar usuários com filtros e ordenação
     */
    public function list(array $filters = [], string $sortField = 'name', string $sortDirection = 'asc'): Collection
    {
        return User::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                });
            })
            ->when(!empty($filters['profile']), function ($query) use ($filters) {
                $query->where('profile', $filters['profile']);
            })
            ->orderBy($sortField, $sortDirection)
            ->get();
    }

    /**
     * Buscar usuário por ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Verificar se email já está em uso
     */
    public function isEmailUnique(string $email, ?int $exceptUserId = null): bool
    {
        return !User::where('email', $email)
            ->when($exceptUserId, function ($query) use ($exceptUserId) {
                $query->where('id', '!=', $exceptUserId);
            })
            ->exists();
    }

    /**
     * Obter lista de perfis disponíveis
     */
    public function getAvailableProfiles(): array
    {
        return [
            'admin' => 'Administrador',
            'approver' => 'Aprovador',
            'requester' => 'Solicitante'
        ];
    }
}
