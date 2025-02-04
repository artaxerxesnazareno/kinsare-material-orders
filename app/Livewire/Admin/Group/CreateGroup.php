<?php

namespace App\Livewire\Admin\Group;

use App\Models\User;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateGroup extends Component
{
    public $name = '';
    public $allowed_balance = '';
    public $approver_id = '';
    public $requester_id = '';
    public $approvers = [];
    public $requesters = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'allowed_balance' => 'required|numeric|min:0',
            'approver_id' => 'required|exists:users,id',
            'requester_id' => 'required|exists:users,id'
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais que 255 caracteres.',
        'allowed_balance.required' => 'O limite é obrigatório.',
        'allowed_balance.numeric' => 'O limite deve ser um número.',
        'allowed_balance.min' => 'O limite deve ser maior ou igual a zero.',
        'approver_id.required' => 'O aprovador é obrigatório.',
        'approver_id.exists' => 'O aprovador selecionado é inválido.',
        'requester_id.required' => 'O solicitante é obrigatório.',
        'requester_id.exists' => 'O solicitante selecionado é inválido.'
    ];

    public function mount()
    {
        $this->approvers = User::where('profile', 'approver')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name
                ];
            });

        $this->requesters = User::where('profile', 'requester')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name
                ];
            });
    }

    public function save(GroupService $groupService)
    {
        $this->validate();

        try {
            $groupService->create([
                'name' => $this->name,
                'allowed_balance' => $this->allowed_balance,
                'approver_id' => $this->approver_id,
                'requester_id' => $this->requester_id
            ], Auth::user());

            session()->flash('success', 'Grupo criado com sucesso!');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.group.create-group');
    }
}
