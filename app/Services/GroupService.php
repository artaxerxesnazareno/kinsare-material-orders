<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Order;
use App\Models\User;
use App\Models\Requester;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class GroupService
{
    /**
     * Criar um novo grupo
     * RNF1: Segurança - Apenas admin pode criar grupos
     */
    public function create(array $data, User $user): Group
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem criar grupos.');
        }

        DB::beginTransaction();
        try {
            // Cria o grupo
            $group = Group::create([
                'name' => $data['name'],
                'allowed_balance' => $data['allowed_balance'],
                'approver_id' => $data['approver_id']
            ]);

            // Associa o solicitante ao grupo
            Requester::create([
                'user_id' => $data['requester_id'],
                'group_id' => $group->id
            ]);

            DB::commit();
            return $group;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar grupo: ' . $e->getMessage());
        }
    }

    /**
     * Verificar saldo disponível do grupo
     * RF3: O sistema deve verificar o saldo permitido do grupo
     */
    public function checkAvailableBalance(Group $group, float $amount): bool
    {
        $usedBalance = Order::where('group_id', $group->id)
            ->where('status', 'approved')
            ->sum('total');

        return ($group->allowed_balance - $usedBalance) >= $amount;
    }

    /**
     * Obter saldo atual do grupo
     */
    public function getCurrentBalance(Group $group): float
    {
        $usedBalance = Order::where('group_id', $group->id)
            ->where('status', 'approved')
            ->sum('total');

        return $group->allowed_balance - $usedBalance;
    }

    /**
     * Listar grupos que o usuário tem acesso
     * RNF1: Segurança - Autorização baseada em perfil
     */
    public function listAccessibleGroups(User $user): Collection
    {
        if ($user->isAdmin()) {
            return Group::with([
                'approver',
                'requesters',
                'orders' => function ($query) {
                    $query->with(['materials', 'requester.user']);
                }
            ])->get();
        }

        if ($user->isApprover()) {
            return Group::where('approver_id', $user->id)
                ->with([
                    'approver',
                    'requesters',
                    'orders' => function ($query) {
                        $query->with(['materials', 'requester.user']);
                    }
                ])->get();
        }

        if ($user->isRequester()) {
            return Group::where('id', $user->requester->group_id)
                ->with([
                    'approver',
                    'requesters',
                    'orders' => function ($query) use ($user) {
                        $query->where('requester_id', $user->requester->id)
                            ->with(['materials', 'requester.user']);
                    }
                ])->get();
        }

        return collect();
    }

    /**
     * Atualizar saldo permitido do grupo
     * RNF1: Segurança - Apenas admin pode alterar saldo
     */
    public function updateAllowedBalance(Group $group, float $newBalance, User $user): Group
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem alterar o saldo permitido dos grupos.');
        }

        if ($newBalance < 0) {
            throw new Exception('O saldo permitido não pode ser negativo.');
        }

        $group->update(['allowed_balance' => $newBalance]);
        return $group->fresh();
    }

    /**
     * Obter relatório de gastos do grupo
     */
    public function getSpendingReport(Group $group, string $startDate, string $endDate): array
    {
        $orders = Order::where('group_id', $group->id)
            ->where('status', 'approved')
            ->whereBetween('created_date', [$startDate, $endDate])
            ->with(['materials', 'requester.user'])
            ->get();

        $totalSpent = $orders->sum('total');
        $orderCount = $orders->count();
        $averageOrderValue = $orderCount > 0 ? $totalSpent / $orderCount : 0;

        $spendingByRequester = $orders->groupBy('requester_id')
            ->map(function ($requesterOrders) {
                return [
                    'requester_name' => $requesterOrders->first()->requester->user->name,
                    'total_spent' => $requesterOrders->sum('total'),
                    'order_count' => $requesterOrders->count()
                ];
            });

        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'total_spent' => $totalSpent,
            'order_count' => $orderCount,
            'average_order_value' => $averageOrderValue,
            'remaining_balance' => $this->getCurrentBalance($group),
            'spending_by_requester' => $spendingByRequester
        ];
    }

    /**
     * Atualizar um grupo existente
     * RNF1: Segurança - Apenas admin pode atualizar grupos
     */
    public function update(Group $group, array $data, User $user): Group
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem atualizar grupos.');
        }

        DB::beginTransaction();
        try {
            // Atualiza o grupo
            $group->update([
                'name' => $data['name'],
                'allowed_balance' => $data['allowed_balance'],
                'approver_id' => $data['approver_id']
            ]);

            // Atualiza ou cria o relacionamento com o solicitante
            Requester::updateOrCreate(
                ['group_id' => $group->id],
                ['user_id' => $data['requester_id']]
            );

            DB::commit();
            return $group;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar grupo: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza o saldo de um grupo
     */
    public function updateBalance(Group $group, float $newBalance, User $user)
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem atualizar o saldo do grupo.');
        }

        if ($newBalance < 0) {
            throw new Exception('O saldo não pode ser negativo.');
        }

        $group->update(['allowed_balance' => $newBalance]);
        return $group;
    }

    /**
     * Remove um grupo
     */
    public function delete(Group $group, User $user)
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem remover grupos.');
        }

        DB::beginTransaction();
        try {
            // Remove os relacionamentos com solicitantes
            Requester::where('group_id', $group->id)->delete();

            // Remove o grupo
            $group->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao remover grupo: ' . $e->getMessage());
        }
    }
}
