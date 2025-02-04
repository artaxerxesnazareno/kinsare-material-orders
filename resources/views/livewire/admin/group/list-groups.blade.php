<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <!-- Mensagens Flash -->
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- Cabeçalho com Pesquisa -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <div class="flex-1">
            <label for="search" class="sr-only">Pesquisar</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       id="search"
                       class="block w-full rounded-lg border-neutral-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                       placeholder="Pesquisar grupos...">
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">
                        <button wire:click="sortBy('name')" class="group inline-flex items-center gap-x-2 text-sm font-semibold text-neutral-900">
                            Nome
                            @if ($sortField === 'name')
                                @if ($sortDirection === 'asc')
                                    <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                @endif
                            @else
                                <svg class="h-5 w-5 text-neutral-400 opacity-0 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left">
                        <button wire:click="sortBy('allowed_balance')" class="group inline-flex items-center gap-x-2 text-sm font-semibold text-neutral-900">
                            Saldo
                            @if ($sortField === 'allowed_balance')
                                @if ($sortDirection === 'asc')
                                    <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                @endif
                            @else
                                <svg class="h-5 w-5 text-neutral-400 opacity-0 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Ações</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($groups as $group)
                    <tr wire:key="{{ $group->id }}">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-neutral-900">{{ $group->name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-neutral-900">AOA {{ number_format($group->allowed_balance, 2, ',', '.') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('groups.edit', $group) }}" class="text-primary-600 hover:text-primary-900">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-neutral-500">
                            Nenhum grupo encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $groups->links() }}
    </div>
</div>
