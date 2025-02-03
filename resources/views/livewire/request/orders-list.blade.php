<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Card de Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Campo de Busca -->
                    <div class="col-span-2">
                        <label for="search" class="sr-only">Buscar pedidos</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   id="search"
                                   wire:loading.class="bg-neutral-50"
                                   wire:loading.attr="disabled"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm disabled:opacity-75 transition-colors duration-200"
                                   placeholder="Buscar pedidos por código ou status..."
                                   wire:model.debounce.300ms="search">
                            <div wire:loading.delay class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="status" class="sr-only">Filtrar por Status</label>
                            <select id="status"
                                    wire:loading.class="bg-neutral-50"
                                    wire:loading.attr="disabled"
                                    class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm disabled:opacity-75 transition-colors duration-200"
                                    wire:model.live="status">
                                <option value="">Todos os Status</option>
                                <option value="new">Novo</option>
                                <option value="in_review">Em Revisão</option>
                                <option value="changes_requested">Alterações Solicitadas</option>
                                <option value="approved">Aprovado</option>
                                <option value="rejected">Rejeitado</option>
                            </select>
                        </div>

                        <select wire:loading.class="bg-neutral-50"
                                wire:loading.attr="disabled"
                                class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm disabled:opacity-75 transition-colors duration-200"
                                wire:model="perPage">
                            <option value="10">10 itens</option>
                            <option value="25">25 itens</option>
                            <option value="50">50 itens</option>
                        </select>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($search || $status)
                    <div class="mt-4 flex items-center gap-2 flex-wrap">
                        @if($search)
                            <span class="inline-flex items-center gap-x-1 rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20">
                                <span>Busca: {{ $search }}</span>
                                <button type="button" wire:click="$set('search', '')" class="group relative -mr-1 h-3.5 w-3.5 rounded-sm hover:bg-primary-600/20">
                                    <span class="sr-only">Remover</span>
                                    <svg viewBox="0 0 14 14" class="h-3.5 w-3.5 stroke-primary-700/50 group-hover:stroke-primary-700/75">
                                        <path d="M4 4l6 6m0-6l-6 6"/>
                                    </svg>
                                </button>
                            </span>
                        @endif

                        @if($status)
                            <span class="inline-flex items-center gap-x-1 rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20">
                                <span>Status:
                                    @switch($status)
                                        @case('new') Novo @break
                                        @case('in_review') Em Revisão @break
                                        @case('changes_requested') Alterações Solicitadas @break
                                        @case('approved') Aprovado @break
                                        @case('rejected') Rejeitado @break
                                    @endswitch
                                </span>
                                <button type="button" wire:click="$set('status', '')" class="group relative -mr-1 h-3.5 w-3.5 rounded-sm hover:bg-primary-600/20">
                                    <span class="sr-only">Remover</span>
                                    <svg viewBox="0 0 14 14" class="h-3.5 w-3.5 stroke-primary-700/50 group-hover:stroke-primary-700/75">
                                        <path d="M4 4l6 6m0-6l-6 6"/>
                                    </svg>
                                </button>
                            </span>
                        @endif

                        <button type="button"
                                wire:click="resetFilters"
                                class="text-xs text-neutral-500 hover:text-neutral-700 font-medium">
                            Limpar Filtros
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lista de Pedidos -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-lg border border-neutral-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 table-fixed">
                        <thead>
                            <tr class="bg-neutral-50/80">
                                <th scope="col" class="px-6 py-4 text-left">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Código</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</span>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Data</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Total</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Grupo</span>
                                </th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white">
                            @forelse ($orders as $order)
                                <tr class="group hover:bg-neutral-50/80 transition-all duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-x-3">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-primary-50 flex items-center justify-center">
                                                <span class="text-sm font-semibold text-primary-700">#{{ substr($order->code, -3) }}</span>
                                            </div>
                                            <div class="text-sm font-medium text-neutral-900">{{ $order->code }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-badge-{{ $order->status }} shadow-sm">
                                            @switch($order->status)
                                                @case('new')
                                                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-blue-400 animate-pulse"></span>
                                                    <span>Novo</span>
                                                    @break
                                                @case('in_review')
                                                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-yellow-400 animate-pulse"></span>
                                                    <span>Em Revisão</span>
                                                    @break
                                                @case('approved')
                                                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-green-400"></span>
                                                    <span>Aprovado</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-red-400"></span>
                                                    <span>Rejeitado</span>
                                                    @break
                                                @default
                                                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-purple-400 animate-pulse"></span>
                                                    <span>Alterações Solicitadas</span>
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-neutral-900">{{ $order->created_at->format('d/m/Y') }}</span>
                                            <span class="text-xs text-neutral-500">{{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-sm font-medium text-neutral-900">
                                                R$ {{ number_format($order->total, 2, ',', '.') }}
                                            </span>
                                            @if($order->total > 1000)
                                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">
                                                    Alto valor
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-x-3">
                                            <div class="h-8 w-8 rounded-lg bg-neutral-100 flex items-center justify-center">
                                                <span class="text-xs font-medium text-neutral-700">
                                                    {{ strtoupper(substr($order->group->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-neutral-900">{{ $order->group->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('orders.show', $order) }}"
                                               class="rounded-lg p-2 text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-all"
                                               title="Ver Detalhes">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            @if($order->status === 'new')
                                                <button type="button"
                                                        class="rounded-lg p-2 text-primary-600 hover:text-primary-900 hover:bg-primary-50 transition-all"
                                                        title="Enviar para Revisão">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="flex flex-col items-center justify-center py-16">
                                            <div class="h-24 w-24 text-neutral-200">
                                                <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                            <h3 class="mt-4 text-lg font-medium text-neutral-900">Nenhum pedido encontrado</h3>
                                            <p class="mt-1 text-sm text-neutral-500">Comece criando um novo pedido de materiais.</p>
                                            <div class="mt-6">
                                                <a href="{{ route('orders.create') }}" class="btn-primary">
                                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Criar Novo Pedido
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
