<?php

namespace App\Livewire\Admin\Group;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditGroup extends Component
{
    public Group $group;
    public $name = '';
    public $balance = '';

    public function mount(Group $group)
    {
        $this->group = $group;
        $this->name = $group->name;
        $this->balance = $group->allowed_balance;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:45|unique:groups,name,' . $this->group->id,
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
            $groupService->update($this->group, [
                'name' => $this->name,
                'balance' => (float) $this->balance
            ], Auth::user());

            session()->flash('success', 'Grupo atualizado com sucesso!');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar grupo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.group.edit-group');
    }
}
