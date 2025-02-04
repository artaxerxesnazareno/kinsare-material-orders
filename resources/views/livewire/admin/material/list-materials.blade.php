<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <!-- Cabeçalho -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">Materiais</h2>
            <p class="mt-1 text-sm text-neutral-500">
                Gerencie os materiais disponíveis para pedidos
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('materials.create') }}"
               class="btn-primary">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Novo Material
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mt-6 sm:flex sm:items-center sm:justify-between">
        <div class="relative max-w-md">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="block w-full rounded-lg border-neutral-300 pl-10 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                   placeholder="Buscar materiais...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>

        <div class="mt-4 sm:mt-0">
            <select wire:model.live="perPage"
                    class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </select>
        </div>
    </div>

    <!-- Lista de Materiais -->
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Nome
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Preço
                        </th>

                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse ($materials as $material)
                        <tr wire:key="{{ $material->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $material->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ number_format($material->price, 2, ',', '.') }} AOA
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                                <a href="{{ route('admin.materials.edit', $material) }}"
                                   class="rounded-lg p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 transition-all"
                                   title="Editar">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-neutral-500">
                                Nenhum material encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="px-6 py-3 bg-neutral-50">
            {{ $materials->links() }}
        </div>
    </div>
</div>
