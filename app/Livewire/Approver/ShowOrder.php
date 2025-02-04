<?php

namespace App\Livewire\Approver;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowOrder extends Component
{
    public Order $order;
    public $showInsufficientBalanceModal = false;
    public $currentOrder = null;

    public function mount(Order $order)
    {
        if ($order->group->approver_id !== Auth::id()) {
            return redirect()->route('approver.dashboard')
                ->with('error', 'Você não tem permissão para visualizar este pedido.');
        }

        $this->order = $order->load(['requester', 'group', 'materials']);
    }

    public function approve()
    {
        try {
            // Verifica se há saldo suficiente
            $usedBalance = $this->order->group->orders()->where('status', 'approved')->sum('total');
            $availableBalance = $this->order->group->allowed_balance - $usedBalance;

            if ($availableBalance < $this->order->total) {
                $this->currentOrder = $this->order;
                $this->showInsufficientBalanceModal = true;
                return;
            }

            app(OrderService::class)->approve($this->order, Auth::user());
            session()->flash('success', 'Pedido aprovado com sucesso!');
            return redirect()->route('approver.dashboard');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function closeInsufficientBalanceModal()
    {
        $this->showInsufficientBalanceModal = false;
        $this->currentOrder = null;
    }

    public function requestChangesFromModal()
    {
        if ($this->currentOrder) {
            $this->requestChanges();
            $this->closeInsufficientBalanceModal();
        }
    }

    public function reject()
    {
        try {
            app(OrderService::class)->reject($this->order, Auth::user(), 'Pedido rejeitado pelo aprovador.');
            session()->flash('success', 'Pedido rejeitado com sucesso!');
            return redirect()->route('approver.dashboard');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function requestChanges()
    {
        try {
            app(OrderService::class)->requestChanges($this->order, Auth::user(), 'Por favor, revise o pedido e faça as alterações necessárias.');
            session()->flash('success', 'Solicitação de alterações enviada com sucesso!');
            return redirect()->route('approver.dashboard');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.show-order');
    }
}
