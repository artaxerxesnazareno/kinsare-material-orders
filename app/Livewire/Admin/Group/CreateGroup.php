<?php

namespace App\Livewire\Admin\Group;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateGroup extends Component
{
    public $name = '';
    public $balance = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:45|unique:groups,name',
            'balance' => 'required|numeric|min:0'
        ];
    }

    protected $messages = [
        'name.required' => 'O nome do grupo é obrigatório.',
        'name.max' => 'O nome do grupo não pode ter mais que 45 caracteres.',
        'name.unique' => 'Já existe um grupo com este nome.',
        'balance.required' => 'O saldo é obrigatório.',
        'balance.numeric' => 'O saldo deve ser um número.',
        'balance.min' => 'O saldo não pode ser negativo.'
    ];

    public function save(GroupService $groupService)
    {
        $this->validate();

        try {
            $groupService->create([
                'name' => $this->name,
                'balance' => (float) $this->balance
            ], Auth::user());

            session()->flash('success', 'Grupo criado com sucesso!');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar grupo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.group.create-group');
    }
}
