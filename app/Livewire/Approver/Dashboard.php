<?php

namespace App\Livewire\Approver;

use App\Models\Order;
use App\Models\Group;
use App\Services\OrderService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'in_review';
    public $perPage = 10;
    public $reason = '';
    public $group;

    protected $orderService;
    protected $paginationTheme = 'tailwind';

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->group = Group::where('approver_id', Auth::id())->first();
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'in_review'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'perPage']);
        $this->resetPage();
    }

    public function approve($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $this->orderService->approve($order, Auth::user());
            $this->group = $this->group->fresh();
            session()->flash('success', 'Pedido aprovado com sucesso!');
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function reject($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $this->orderService->reject($order, Auth::user(), 'Pedido rejeitado pelo aprovador.');
            session()->flash('success', 'Pedido rejeitado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function requestChanges($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $this->orderService->requestChanges($order, Auth::user(), 'Por favor, revise o pedido e faça as alterações necessárias.');
            session()->flash('success', 'Solicitação de alterações enviada com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $query = Order::query()
            ->with(['requester.user', 'group'])
            ->whereHas('group', function ($query) {
                $query->where('id', $this->group->id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('requester.user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('group', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            });

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.approver.dashboard', [
            'orders' => $orders
        ]);
    }
}
