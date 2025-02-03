<?php

namespace App\Livewire\Request\Order;

use App\Models\Material;
use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends Component
{
    public $materials = [];
    public $selectedMaterials = [];
    public $total = 0;

    public function mount()
    {
        $this->materials = Material::orderBy('name')->get();
        $this->selectedMaterials = [
            ['material_id' => '', 'quantity' => 1]
        ];
    }

    public function addMaterial()
    {
        $this->selectedMaterials[] = ['material_id' => '', 'quantity' => 1];
    }

    public function removeMaterial($index)
    {
        unset($this->selectedMaterials[$index]);
        $this->selectedMaterials = array_values($this->selectedMaterials);
        $this->calculateTotal();
    }

    public function updatedSelectedMaterials()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->selectedMaterials as $item) {
            if (!empty($item['material_id']) && !empty($item['quantity'])) {
                $material = $this->materials->firstWhere('id', $item['material_id']);
                if ($material) {
                    $this->total += $material->price * $item['quantity'];
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'selectedMaterials' => 'required|array|min:1',
            'selectedMaterials.*.material_id' => 'required|exists:materials,id',
            'selectedMaterials.*.quantity' => 'required|integer|min:1'
        ], [
            'selectedMaterials.required' => 'Você precisa adicionar pelo menos um material',
            'selectedMaterials.*.material_id.required' => 'Selecione um material',
            'selectedMaterials.*.material_id.exists' => 'Material inválido',
            'selectedMaterials.*.quantity.required' => 'Informe a quantidade',
            'selectedMaterials.*.quantity.integer' => 'A quantidade deve ser um número inteiro',
            'selectedMaterials.*.quantity.min' => 'A quantidade mínima é 1'
        ]);

        try {
            $order = Order::create([
                'total' => $this->total,
                'status' => 'new',
                'created_date' => now(),
                'updated_date' => now(),
                'requester_id' => Auth::user()->requester->id,
                'group_id' => Auth::user()->requester->group_id
            ]);

            foreach ($this->selectedMaterials as $item) {
                $material = Material::find($item['material_id']);
                $order->materials()->attach($material->id, [
                    'quantity' => $item['quantity'],
                    'subtotal' => $material->price * $item['quantity']
                ]);
            }

            session()->flash('success', 'Pedido criado com sucesso!');
            return redirect()->route('orders.show', $order);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.request.order.create-order');
    }
}
