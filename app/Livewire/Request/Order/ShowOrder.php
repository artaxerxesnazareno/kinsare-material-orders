<?php

namespace App\Livewire\Request\Order;

use App\Models\Order;
use Livewire\Component;

class ShowOrder extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load(['materials', 'group', 'requester.user']);
    }

    public function sendToReview()
    {
        if ($this->order->status !== 'new') {
            session()->flash('error', 'Apenas pedidos novos podem ser enviados para revisão.');
            return;
        }

        try {
            $this->order->update([
                'status' => 'in_review',
                'updated_date' => now()
            ]);

            session()->flash('success', 'Pedido enviado para revisão com sucesso!');
            $this->order = $this->order->fresh(['materials', 'group', 'requester.user']);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao enviar pedido para revisão: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.request.order.show-order');
    }
}
