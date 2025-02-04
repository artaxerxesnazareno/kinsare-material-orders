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
                       placeholder="Digite o nome do grupo">
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Limite -->
        <div>
            <label for="allowed_balance" class="block text-sm font-medium text-neutral-700">
                Limite
            </label>
            <div class="mt-1">
                <input type="number"
                       id="allowed_balance"
                       wire:model="allowed_balance"
                       step="0.01"
                       min="0"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Digite o limite do grupo">
            </div>
            @error('allowed_balance')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Aprovador -->
        <div>
            <label for="approver_id" class="block text-sm font-medium text-neutral-700">
                Aprovador
            </label>
            <div class="mt-1">
                <select id="approver_id"
                        wire:model="approver_id"
                        class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">Selecione um aprovador</option>
                    @foreach ($approvers as $approver)
                        <option value="{{ $approver['id'] }}">{{ $approver['name'] }}</option>
                    @endforeach
                </select>
            </div>
            @error('approver_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Solicitante -->
        <div>
            <label for="requester_id" class="block text-sm font-medium text-neutral-700">
                Solicitante
            </label>
            <div class="mt-1">
                <select id="requester_id"
                        wire:model="requester_id"
                        class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">Selecione um solicitante</option>
                    @foreach ($requesters as $requester)
                        <option value="{{ $requester['id'] }}">{{ $requester['name'] }}</option>
                    @endforeach
                </select>
            </div>
            @error('requester_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-x-3">
            <a href="{{ route('groups.index') }}"
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
                <span wire:loading.remove wire:target="save">Criar Grupo</span>
                <span wire:loading wire:target="save">Criando...</span>
            </button>
        </div>
    </form>
</div>
