<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-xl">
        <!-- Logo ou Título -->
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-display font-bold text-gray-900 dark:text-gray-100">
                {{ __('Bem-vindo de volta') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Por favor, faça login na sua conta') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('E-mail')" class="text-sm font-medium" />
                <x-text-input wire:model="form.email"
                    id="email"
                    class="block mt-2 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-primary-500 focus:border-primary-500"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="seu@email.com" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Senha')" class="text-sm font-medium" />
                <x-text-input wire:model="form.password"
                    id="password"
                    class="block mt-2 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-primary-500 focus:border-primary-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember"
                        id="remember"
                        type="checkbox"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary-600 shadow-sm focus:ring-primary-500 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800"
                        name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Lembrar-me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 hover:underline"
                        href="{{ route('password.request') }}"
                        wire:navigate>
                        {{ __('Esqueceu a senha?') }}
                    </a>
                @endif
            </div>

            <div>
                <x-primary-button class="w-full justify-center py-3 text-sm font-semibold">
                    {{ __('Entrar') }}
                </x-primary-button>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                {{ __('Não tem uma conta?') }}
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 hover:underline" wire:navigate>
                    {{ __('Cadastre-se') }}
                </a>
            </p>
        </form>
    </div>
</div>
