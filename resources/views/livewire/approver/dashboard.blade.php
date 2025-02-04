<div>
    <div wire:poll.10s wire:init="$refresh">
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <!-- Cabeçalho -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900">Dashboard do Aprovador</h2>
        <p class="mt-1 text-sm text-neutral-500">Gerencie os pedidos que necessitam da sua aprovação.</p>
    </div>

    <!-- Informações do Grupo -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-4">
                <div class="h-16 w-16 rounded-xl bg-primary-50 flex items-center justify-center">
                    <span class="text-xl font-bold text-primary-700">
                        {{ strtoupper(substr($group->name, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">{{ $group->name }}</h3>
                    <p class="text-sm text-neutral-500">Você é o aprovador deste grupo</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-neutral-500">Saldo Disponível</div>
                <div class="text-2xl font-bold text-neutral-900">
                    @php
                        $usedBalance = $group->orders()->where('status', 'approved')->sum('total');
                        $availableBalance = $group->allowed_balance - $usedBalance;
                    @endphp
                    {{ number_format($availableBalance, 2, ',', '.') }} AOA
                </div>
                @if($availableBalance < 1000)
                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-800 ring-1 ring-inset ring-red-600/20">
                        Saldo Baixo
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pedidos em Revisão -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Em Revisão</h3>
                    <p class="text-sm text-neutral-500">{{ $orders->where('status', 'in_review')->count() }} pedidos</p>
                </div>
            </div>
        </div>

        <!-- Pedidos Aprovados -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Aprovados</h3>
                    <p class="text-sm text-neutral-500">{{ $orders->where('status', 'approved')->count() }} pedidos</p>
                </div>
            </div>
        </div>

        <!-- Pedidos Rejeitados -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Rejeitados</h3>
                    <p class="text-sm text-neutral-500">{{ $orders->where('status', 'rejected')->count() }} pedidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 mb-6">
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
                               wire:model.debounce.300ms="search"
                               class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                               placeholder="Buscar por ID, solicitante ou grupo...">
                    </div>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="status" class="sr-only">Filtrar por Status</label>
                        <select id="status"
                                wire:model.live="status"
                                class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <option value="">Todos</option>
                            <option value="in_review">Em Revisão</option>
                            <option value="approved">Aprovados</option>
                            <option value="rejected">Rejeitados</option>
                            <option value="changes_requested">Alterações Solicitadas</option>
                        </select>
                    </div>

                    <select wire:model.live="perPage"
                            class="block w-full rounded-lg border-neutral-300 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
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
                                    @case('in_review') Em Revisão @break
                                    @case('approved') Aprovados @break
                                    @case('rejected') Rejeitados @break
                                    @case('changes_requested') Alterações Solicitadas @break
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
    <div class="bg-white rounded-xl shadow-lg border border-neutral-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead>
                    <tr class="bg-neutral-50/80">
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Código</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Solicitante</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Grupo</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Data</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Total</span>
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
                                    <div>
                                        <div class="text-sm font-medium text-neutral-900">{{ $order->code }}</div>
                                        <div class="text-xs text-neutral-500">  {{ $order->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-neutral-100 flex items-center justify-center">
                                        <span class="text-xs font-medium text-neutral-700">
                                            {{ strtoupper(substr($order->requester->user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-neutral-900">{{ $order->requester->user->name }}</span>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-badge-{{ $order->status }} shadow-sm">
                                    @switch($order->status)
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
                                        {{ number_format($order->total, 2, ',', '.') }} AOA
                                    </span>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-x-2">
                                    <a href="{{ route('approver.orders.show', $order) }}"
                                       class="rounded-lg p-2 text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-all"
                                       title="Ver Detalhes">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    @if($order->status === 'in_review' || $order->status === 'new')
                                        <button type="button"
                                                wire:click="approve({{ $order->id }})"
                                                wire:confirm="Tem certeza que deseja aprovar este pedido?"
                                                class="rounded-lg p-2 text-neutral-400 hover:text-green-600 hover:bg-green-50 transition-all"
                                                title="Aprovar Pedido">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>

                                        <button type="button"
                                                wire:click="reject({{ $order->id }})"
                                                wire:confirm="Tem certeza que deseja rejeitar este pedido?"
                                                class="rounded-lg p-2 text-neutral-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                                title="Rejeitar Pedido">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>

                                        <button type="button"
                                                wire:click="requestChanges({{ $order->id }})"
                                                wire:confirm="Tem certeza que deseja solicitar alterações neste pedido?"
                                                class="rounded-lg p-2 text-neutral-400 hover:text-purple-600 hover:bg-purple-50 transition-all"
                                                title="Solicitar Alterações">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="flex flex-col items-center justify-center py-16">
                                    <div class="h-24 w-24 text-neutral-200">
                                        <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-medium text-neutral-900">Nenhum pedido encontrado</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Não há pedidos para revisar no momento.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
