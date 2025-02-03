<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <!-- Cabeçalho -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900">Detalhes do Pedido</h2>
                <p class="mt-1 text-sm text-neutral-500">Visualize e gerencie os detalhes do pedido #{{ substr($order->code, -3) }}</p>
            </div>

            <div class="flex items-center gap-x-3">
                <a href="{{ route('approver.dashboard') }}"
                   class="btn-neutral">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar
                </a>

                @if($order->status === 'in_review')
                    <button type="button"
                            wire:click="approve"
                            wire:confirm="Tem certeza que deseja aprovar este pedido?"
                            class="btn-success">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Aprovar Pedido
                    </button>

                    <button type="button"
                            wire:click="reject"
                            wire:confirm="Tem certeza que deseja rejeitar este pedido?"
                            class="btn-danger">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Rejeitar Pedido
                    </button>

                    <button type="button"
                            wire:click="requestChanges"
                            wire:confirm="Tem certeza que deseja solicitar alterações neste pedido?"
                            class="btn-warning">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Solicitar Alterações
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Informações do Pedido -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-neutral-100 flex items-center justify-center">
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
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Status</h3>
                    <p class="text-sm text-neutral-500">Atualizado em {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Solicitante -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-neutral-100 flex items-center justify-center">
                    <span class="text-lg font-semibold text-neutral-700">
                        {{ strtoupper(substr($order->requester->name, 0, 2)) }}
                    </span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Solicitante</h3>
                    <p class="text-sm text-neutral-500">{{ $order->requester->name }}</p>
                </div>
            </div>
        </div>

        <!-- Grupo -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-neutral-100 flex items-center justify-center">
                    <span class="text-lg font-semibold text-neutral-700">
                        {{ strtoupper(substr($order->group->name, 0, 2)) }}
                    </span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Grupo</h3>
                    <p class="text-sm text-neutral-500">{{ $order->group->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Materiais -->
    <div class="bg-white rounded-xl shadow-lg border border-neutral-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead>
                    <tr class="bg-neutral-50/80">
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Material</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Quantidade</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Preço Unitário</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Subtotal</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white">
                    @foreach($order->materials as $material)
                        <tr class="hover:bg-neutral-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-900">{{ $material->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-900">{{ $material->pivot->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-900"> {{ number_format($material->price, 2, ',', '.') }} AOA</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-900">
                                    {{ number_format($material->price * $material->pivot->quantity, 2, ',', '.') }} AOA
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Total -->
                    <tr class="bg-neutral-50/80">
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-neutral-900">Total:</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-neutral-900">
                                {{ number_format($order->total, 2, ',', '.') }} AOA
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
