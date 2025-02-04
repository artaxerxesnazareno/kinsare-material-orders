<?php

namespace App\Livewire\Admin\User;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditUser extends Component
{
    public $user;
    public $userId;
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'password' => 'nullable|string|min:8|confirmed',
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
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'A confirmação da senha não corresponde.',
        'profile.required' => 'O perfil é obrigatório.',
        'profile.in' => 'O perfil selecionado é inválido.'
    ];

    public function mount($user, $profiles)
    {
        $this->user = $user;
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile = $user->profile;
        $this->profiles = $profiles;
    }

    public function save(UserService $userService)
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'profile' => $this->profile
            ];

            if ($this->password) {
                $data['password'] = $this->password;
            }

            $userService->update($this->user, $data, Auth::user());

            session()->flash('success', 'Usuário atualizado com sucesso!');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.user.edit-user');
    }
}
