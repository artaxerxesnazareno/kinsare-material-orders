<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use Livewire\Component;

class CreateMaterial extends Component
{
    public $name = '';
    public $price = '';
    public $description = '';

    protected $rules = [
        'name' => 'required|string|max:45|unique:materials',
        'price' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255'
    ];

    protected $messages = [
        'name.required' => 'O nome do material é obrigatório.',
        'name.max' => 'O nome do material não pode ter mais que 45 caracteres.',
        'name.unique' => 'Já existe um material com este nome.',
        'price.required' => 'O preço é obrigatório.',
        'price.numeric' => 'O preço deve ser um número.',
        'price.min' => 'O preço deve ser maior que zero.',
        'description.max' => 'A descrição não pode ter mais que 255 caracteres.'
    ];

    public function save()
    {
        $this->validate();

        try {
            Material::create([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description
            ]);

            session()->flash('success', 'Material criado com sucesso!');
            return redirect()->route('admin.materials.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar material: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.material.create-material');
    }
}
