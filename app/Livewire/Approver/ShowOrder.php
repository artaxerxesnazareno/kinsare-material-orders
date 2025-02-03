<?php

namespace App\Livewire\Approver;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Component;

class ShowOrder extends Component
{
    public Order $order;
    protected $orderService;

    public function mount(Order $order, OrderService $orderService)
    {
        if ($order->group->approver_id !== auth()->id()) {
            return redirect()->route('approver.dashboard')
                ->with('error', 'Você não tem permissão para visualizar este pedido.');
        }

        $this->order = $order->load(['requester', 'group', 'materials']);
        $this->orderService = $orderService;
    }

    public function approve()
    {
        try {
            $this->orderService->approve($this->order, auth()->user());
            session()->flash('success', 'Pedido aprovado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function reject()
    {
        try {
            $this->orderService->reject($this->order, auth()->user(), 'Pedido rejeitado pelo aprovador.');
            session()->flash('success', 'Pedido rejeitado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function requestChanges()
    {
        try {
            $this->orderService->requestChanges($this->order, auth()->user(), 'Por favor, revise o pedido e faça as alterações necessárias.');
            session()->flash('success', 'Solicitação de alterações enviada com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.show-order');
    }
}
