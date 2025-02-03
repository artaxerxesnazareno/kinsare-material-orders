<x-app-layout>
    <x-slot name="header">
        <div class="bg-white shadow-sm border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-3xl font-display font-bold text-neutral-900 sm:tracking-tight">
                            Meus Pedidos
                        </h1>
                        <p class="mt-2 text-lg text-neutral-600">
                            Gerencie todos os seus pedidos de materiais
                        </p>
                    </div>
                    <div class="mt-5 flex lg:ml-4 lg:mt-0">
                        <a href="{{ route('orders.create') }}"
                           class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Novo Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
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
                                       class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                       placeholder="Buscar pedidos por código ou status..."
                                       wire:model.debounce.300ms="search">
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="grid grid-cols-2 gap-3">
                            <select class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                    wire:model="status">
                                <option value="">Status</option>
                                <option value="new">Novo</option>
                                <option value="in_review">Em Revisão</option>
                                <option value="changes_requested">Alterações Solicitadas</option>
                                <option value="approved">Aprovado</option>
                                <option value="rejected">Rejeitado</option>
                            </select>

                            <select class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                    wire:model="perPage">
                                <option value="10">10 itens</option>
                                <option value="25">25 itens</option>
                                <option value="50">50 itens</option>
                            </select>
                        </div>
                    </div>
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
                                            <button class="text-neutral-400 hover:text-neutral-600 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Data</span>
                                            <button class="text-neutral-400 hover:text-neutral-600 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Total</span>
                                            <button class="text-neutral-400 hover:text-neutral-600 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </button>
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

        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
