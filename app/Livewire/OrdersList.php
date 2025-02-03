<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = null;
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = ['filterByStatus' => 'setStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function setStatus($status)
    {
        $this->status = $status['status'];
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('code', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            });

        // Debug temporário
        \Log::info('Status selecionado: ' . $this->status);
        \Log::info('SQL: ' . $query->toSql());

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.orders-list', [
            'orders' => $orders
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function sendToReview($orderId)
    {
        $order = Order::find($orderId);

        if ($order && $order->status === 'new') {
            $order->update(['status' => 'in_review']);
            $this->dispatch('order-updated', orderId: $orderId);
        }
    }
}
