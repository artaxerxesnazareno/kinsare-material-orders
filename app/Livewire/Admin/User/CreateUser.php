<?php

namespace App\Livewire\Admin\User;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateUser extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $profile = '';
    public $profiles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile' => 'required|string|in:' . implode(',', array_keys($this->profiles))
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais que 255 caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'O email deve ser um endereço válido.',
        'email.max' => 'O email não pode ter mais que 255 caracteres.',
        'email.unique' => 'Este email já está em uso.',
        'password.required' => 'A senha é obrigatória.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'A confirmação da senha não corresponde.',
        'profile.required' => 'O perfil é obrigatório.',
        'profile.in' => 'O perfil selecionado é inválido.'
    ];

    public function mount($profiles)
    {
        $this->profiles = $profiles;
    }

    public function save(UserService $userService)
    {
        $this->validate();

        try {
            $userService->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'profile' => $this->profile
            ], Auth::user());

            session()->flash('success', 'Usuário criado com sucesso!');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.user.create-user');
    }
}
