<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Material;
use App\Models\Requester;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderService
{
    /**
     * Cria um novo pedido com materiais
     *
     * @param int $requesterId ID do solicitante
     * @param array $materials Array de materiais com suas quantidades [['material_id' => 1, 'quantity' => 2], ...]
     * @return Order
     */
    public function createOrder(int $requesterId, array $materials): Order
    {
        if (empty($materials)) {
            throw new \InvalidArgumentException('O pedido deve conter pelo menos um material');
        }

        $requester = Requester::findOrFail($requesterId);


        DB::beginTransaction();
        try {
            $order = Order::create([
                'requester_id' => $requesterId,
                'group_id' => $requester->group_id,
                'status' => 'new',
                'total' => 0,
            ]);

            $total = 0;
            foreach ($materials as $item) {
                if (!isset($item['material_id']) || !isset($item['quantity'])) {
                    throw new \InvalidArgumentException('Material ID e quantidade são obrigatórios');
                }

                if ($item['quantity'] <= 0) {
                    throw new \InvalidArgumentException('A quantidade deve ser maior que zero');
                }

                $material = Material::findOrFail($item['material_id']);

                $quantity = $item['quantity'];
                $subtotal = $material->price * $quantity;

                $total += $subtotal;

                $order->materials()->attach($material->id, [
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);
            DB::commit();

            return $order->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Atualiza o status de um pedido
     *
     * @param int $orderId ID do pedido
     * @param string $status Novo status ('new', 'under_review', 'changes_requested', 'approved', 'rejected')
     * @return Order
     */
    public function updateOrderStatus(int $orderId, string $status): Order
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);

        return $order->fresh();
    }

    /**
     * Obtém todos os pedidos de um grupo
     *
     * @param int $groupId ID do grupo
     * @return Collection
     */
    public function getOrdersByGroup(int $groupId): Collection
    {
        return Order::where('group_id', $groupId)
            ->with(['requester', 'materials'])
            ->get();
    }

    /**
     * Obtém todos os pedidos de um solicitante
     *
     * @param int $requesterId ID do solicitante
     * @return Collection
     */
    public function getOrdersByRequester(int $requesterId): Collection
    {
        return Order::where('requester_id', $requesterId)
            ->with(['materials'])
            ->get();
    }

    /**
     * Calcula o total gasto por um grupo em um período
     *
     * @param int $groupId ID do grupo
     * @param string $startDate Data inicial (Y-m-d)
     * @param string $endDate Data final (Y-m-d)
     * @return float
     */
    public function calculateGroupSpending(int $groupId, string $startDate, string $endDate): float
    {
        return Order::where('group_id', $groupId)
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
    }

    /**
     * Deleta um pedido e suas relações
     *
     * @param int $orderId ID do pedido
     * @return bool
     */
    public function deleteOrder(int $orderId): bool
    {
        $order = Order::findOrFail($orderId);
        return $order->delete();
    }

    /**
     * Criar um novo pedido
     * RF1: O usuário solicitante pode criar um pedido e incluir diversos materiais
     */
    public function create(array $data, User $user): Order
    {
        if (!$user->isRequester()) {
            throw new Exception('Apenas solicitantes podem criar pedidos.');
        }

        $requester = $user->requester;
        if (!$requester) {
            throw new Exception('Usuário não está vinculado a um grupo como solicitante.');
        }

        $total = $this->calculateOrderTotal($data['materials']);

        try {
            return DB::transaction(function () use ($data, $requester, $total) {
                $order = Order::create([
                    'requester_id' => $requester->id,
                    'group_id' => $requester->group_id,
                    'total' => $total,
                    'status' => 'new',
                    'created_date' => now(),
                    'updated_date' => now()
                ]);

                foreach ($data['materials'] as $materialData) {
                    $material = Material::findOrFail($materialData['id']);
                    $subtotal = $material->price * $materialData['quantity'];

                    $order->materials()->attach($material->id, [
                        'quantity' => $materialData['quantity'],
                        'subtotal' => $subtotal
                    ]);
                }

                return $order;
            });
        } catch (Exception $e) {
            throw new Exception('Erro ao criar o pedido: ' . $e->getMessage());
        }
    }

    /**
     * Aprovar um pedido
     * RF2: O aprovador pode aprovar pedidos se houver saldo suficiente
     * RF3: O sistema deve verificar o saldo permitido do grupo
     */
    public function approve(Order $order, User $user): Order
    {
        if (!$user->isApprover()) {
            throw new Exception('Apenas aprovadores podem aprovar pedidos.');
        }

        if (!in_array($order->status, ['new', 'in_review'])) {
            throw new Exception('Apenas pedidos novos ou em revisão podem ser aprovados.');
        }

        if (!$user->approverGroups->contains('id', $order->group_id)) {
            throw new Exception('Você não tem permissão para aprovar pedidos deste grupo.');
        }

        if (!$order->requester->group->hasAvailableBalance($order->total)) {
            throw new Exception('Saldo insuficiente no grupo para aprovar este pedido.');
        }

        try {
            return DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'approved',
                    'updated_date' => now()
                ]);

                return $order->fresh();
            });
        } catch (Exception $e) {
            throw new Exception('Erro ao aprovar o pedido: ' . $e->getMessage());
        }
    }

    /**
     * Rejeitar um pedido
     * RF2: O aprovador pode rejeitar pedidos
     */
    public function reject(Order $order, User $user, string $reason): Order
    {
        if (!$user->isApprover()) {
            throw new Exception('Apenas aprovadores podem rejeitar pedidos.');
        }

        if (!in_array($order->status, ['new', 'in_review'])) {
            throw new Exception('Apenas pedidos novos ou em revisão podem ser rejeitados.');
        }

        if (!$user->approverGroups->contains('id', $order->group_id)) {
            throw new Exception('Você não tem permissão para rejeitar pedidos deste grupo.');
        }

        try {
            return DB::transaction(function () use ($order, $reason) {
                $order->update([
                    'status' => 'rejected',
                    'rejection_reason' => $reason,
                    'updated_date' => now()
                ]);

                return $order->fresh();
            });
        } catch (Exception $e) {
            throw new Exception('Erro ao rejeitar o pedido: ' . $e->getMessage());
        }
    }

    /**
     * Solicitar alterações em um pedido
     * RF2: O aprovador pode solicitar alterações no pedido
     */
    public function requestChanges(Order $order, User $user, string $changes): Order
    {
        if (!$user->isApprover()) {
            throw new Exception('Apenas aprovadores podem solicitar alterações em pedidos.');
        }

        if (!in_array($order->status, ['new', 'in_review'])) {
            throw new Exception('Apenas pedidos novos ou em revisão podem receber solicitações de alteração.');
        }

        if (!$user->approverGroups->contains('id', $order->group_id)) {
            throw new Exception('Você não tem permissão para solicitar alterações em pedidos deste grupo.');
        }

        try {
            return DB::transaction(function () use ($order, $changes) {
                $order->update([
                    'status' => 'changes_requested',
                    'change_request' => $changes,
                    'updated_date' => now()
                ]);

                return $order->fresh();
            });
        } catch (Exception $e) {
            throw new Exception('Erro ao solicitar alterações no pedido: ' . $e->getMessage());
        }
    }

    /**
     * Enviar pedido para revisão
     * RF4: Atualização do estado do pedido
     */
    public function sendToReview(Order $order, User $user): Order
    {
        if (!$user->isRequester()) {
            throw new Exception('Apenas solicitantes podem enviar pedidos para revisão.');
        }

        if ($order->requester_id !== $user->requester->id) {
            throw new Exception('Você só pode enviar para revisão seus próprios pedidos.');
        }

        if ($order->status !== 'new' && $order->status !== 'changes_requested') {
            throw new Exception('Apenas pedidos novos ou com alterações solicitadas podem ser enviados para revisão.');
        }

        try {
            return DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'in_review',
                    'updated_date' => now()
                ]);

                return $order->fresh();
            });
        } catch (Exception $e) {
            throw new Exception('Erro ao enviar pedido para revisão: ' . $e->getMessage());
        }
    }

    /**
     * Listar pedidos com base no perfil do usuário
     * RNF1: Segurança - Autorização baseada em perfil
     */
    public function list(User $user): Collection
    {
        if ($user->isAdmin()) {
            return Order::with(['requester.user', 'group', 'materials'])->get();
        }

        if ($user->isApprover()) {
            return Order::whereIn('group_id', $user->approverGroups->pluck('id'))
                ->with(['requester.user', 'group', 'materials'])
                ->get();
        }

        if ($user->isRequester()) {
            return Order::where('requester_id', $user->requester->id)
                ->with(['requester.user', 'group', 'materials'])
                ->get();
        }

        return collect();
    }

    /**
     * Calcular o total do pedido
     */
    private function calculateOrderTotal(array $materials): float
    {
        $total = 0;

        foreach ($materials as $materialData) {
            $material = Material::findOrFail($materialData['id']);
            $total += $material->price * $materialData['quantity'];
        }

        return $total;
    }
}
