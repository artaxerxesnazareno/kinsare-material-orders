<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Nome -->
        <div>
            <label for="name" class="block text-sm font-medium text-neutral-700">
                Nome
            </label>
            <div class="mt-1">
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Digite o nome do usuário">
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-neutral-700">
                Email
            </label>
            <div class="mt-1">
                <input type="email"
                       id="email"
                       wire:model="email"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Digite o email do usuário">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Senha (opcional) -->
        <div>
            <label for="password" class="block text-sm font-medium text-neutral-700">
                Nova Senha (opcional)
            </label>
            <div class="mt-1">
                <input type="password"
                       id="password"
                       wire:model="password"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Digite a nova senha">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmação de Senha -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-neutral-700">
                Confirmar Nova Senha
            </label>
            <div class="mt-1">
                <input type="password"
                       id="password_confirmation"
                       wire:model="password_confirmation"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Confirme a nova senha">
            </div>
        </div>

        <!-- Perfil -->
        <div>
            <label for="profile" class="block text-sm font-medium text-neutral-700">
                Perfil
            </label>
            <div class="mt-1">
                <select id="profile"
                        wire:model="profile"
                        class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">Selecione um perfil</option>
                    @foreach ($profiles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @error('profile')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-x-3">
            <a href="{{ route('users.index') }}"
               class="btn-neutral">
                Cancelar
            </a>

            <button type="submit"
                    class="btn-primary"
                    wire:loading.attr="disabled"
                    wire:target="save">
                <svg wire:loading
                     wire:target="save"
                     class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"></circle>
                    <path class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Salvar Alterações</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
