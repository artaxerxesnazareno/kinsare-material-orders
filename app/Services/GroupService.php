<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Exception;

class GroupService
{
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
            return Group::with(['approver', 'requesters'])->get();
        }

        if ($user->isApprover()) {
            return Group::where('approver_id', $user->id)
                ->with(['approver', 'requesters'])
                ->get();
        }

        if ($user->isRequester()) {
            return Group::where('id', $user->requester->group_id)
                ->with(['approver', 'requesters'])
                ->get();
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
}
