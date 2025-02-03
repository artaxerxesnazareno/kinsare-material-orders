<?php

namespace App\Livewire\Request\Order;

use App\Models\Material;
use App\Models\Order;
use Livewire\Component;

class EditOrder extends Component
{
    public Order $order;
    public $materials = [];
    public $selectedMaterials = [];
    public $total = 0;

    public function mount(Order $order)
    {
        $this->order = $order->load(['materials', 'group', 'requester.user']);
        $this->materials = Material::orderBy('name')->get();

        // Carrega os materiais existentes do pedido
        $this->selectedMaterials = $this->order->materials->map(function ($material) {
            return [
                'material_id' => $material->id,
                'quantity' => $material->pivot->quantity
            ];
        })->toArray();

        $this->calculateTotal();
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
        if (!in_array($this->order->status, ['new', 'changes_requested'])) {
            session()->flash('error', 'Este pedido não pode ser editado no status atual.');
            return;
        }

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
            $this->order->update([
                'total' => $this->total,
                'updated_date' => now()
            ]);

            // Remove todos os materiais existentes
            $this->order->materials()->detach();

            // Adiciona os novos materiais
            foreach ($this->selectedMaterials as $item) {
                $material = Material::find($item['material_id']);
                $this->order->materials()->attach($material->id, [
                    'quantity' => $item['quantity'],
                    'subtotal' => $material->price * $item['quantity']
                ]);
            }

            session()->flash('success', 'Pedido atualizado com sucesso!');
            return redirect()->route('orders.show', $this->order);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar pedido: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.request.order.edit-order');
    }
}
