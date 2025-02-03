<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Card de Materiais -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200">
            <div class="p-6">
                <h2 class="text-lg font-medium text-neutral-900 mb-4">Materiais do Pedido</h2>

                <div class="space-y-4">
                    @foreach($selectedMaterials as $index => $material)
                        <div class="flex items-start gap-4" wire:key="material-{{ $index }}">
                            <div class="flex-1">
                                <label for="material_{{ $index }}" class="sr-only">Material</label>
                                <select id="material_{{ $index }}"
                                        wire:model.live="selectedMaterials.{{ $index }}.material_id"
                                        class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    <option value="">Selecione um material</option>
                                    @foreach($materials as $mat)
                                        <option value="{{ $mat->id }}">
                                            {{ $mat->name }} - R$ {{ number_format($mat->price, 2, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("selectedMaterials.{$index}.material_id")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-32">
                                <label for="quantity_{{ $index }}" class="sr-only">Quantidade</label>
                                <input type="number"
                                       id="quantity_{{ $index }}"
                                       wire:model.live="selectedMaterials.{{ $index }}.quantity"
                                       min="1"
                                       class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                       placeholder="Qtd">
                                @error("selectedMaterials.{$index}.quantity")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                @if(count($selectedMaterials) > 1)
                                    <button type="button"
                                            wire:click="removeMaterial({{ $index }})"
                                            class="rounded-lg p-2 text-neutral-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                        <span class="sr-only">Remover material</span>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-4">
                        <button type="button"
                                wire:click="addMaterial"
                                class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                            <svg class="h-5 w-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar Material
                        </button>
                    </div>
                </div>
            </div>

            <!-- Rodapé com Total -->
            <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-neutral-700">Total do Pedido</span>
                    <span class="text-lg font-semibold text-neutral-900">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex items-center justify-end gap-x-3">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-full">
                Criar Pedido
            </button>
        </div>
    </form>
</div>
