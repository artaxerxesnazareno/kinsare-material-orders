<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Nome do Material -->
        <div>
            <label for="name" class="block text-sm font-medium text-neutral-700">
                Nome do Material
            </label>
            <div class="mt-1">
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="Digite o nome do material">
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Preço -->
        <div>
            <label for="price" class="block text-sm font-medium text-neutral-700">
                Preço (AOA)
            </label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-neutral-500 sm:text-sm">AOA</span>
                </div>
                <input type="number"
                       id="price"
                       wire:model="price"
                       step="0.01"
                       min="0.01"
                       class="block w-full pl-12 rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                       placeholder="0,00">
            </div>
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-x-3 space-x-8">
            <a href="{{ route('materials.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                Cancelar
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-x-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200"
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
                <span wire:loading.remove wire:target="save">Atualizar Material</span>
                <span wire:loading wire:target="save">Atualizando...</span>
            </button>
        </div>
    </form>
</div>
