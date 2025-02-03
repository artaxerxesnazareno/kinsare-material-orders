<div>
    <!-- Indicador de Carregamento -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-primary-500 overflow-hidden">
            <div class="w-full h-full origin-left-right bg-primary-300 animate-progress"></div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Card de Status -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-neutral-900">Pedido #{{ $order->code }}</h2>
                        <p class="mt-1 text-sm text-neutral-500">
                            Criado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                        </p>
                    </div>
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
                </div>

                <!-- Informações do Grupo -->
                <div class="mt-6 border-t border-neutral-200 pt-6">
                    <div class="flex items-center gap-x-3">
                        <div class="h-10 w-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-neutral-700">
                                {{ strtoupper(substr($order->group->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-neutral-900">{{ $order->group->name }}</h3>
                            <p class="mt-1 text-xs text-neutral-500">
                                Saldo disponível: R$ {{ number_format($order->group->allowed_balance, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Materiais -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200">
            <div class="p-6">
                <h2 class="text-lg font-medium text-neutral-900 mb-4">Materiais do Pedido</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead>
                            <tr class="bg-neutral-50">
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-neutral-900">Material</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-neutral-900">Quantidade</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-neutral-900">Valor Unitário</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-neutral-900">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">
                            @foreach($order->materials as $material)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-900">
                                        {{ $material->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-900 text-right">
                                        {{ $material->pivot->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-900 text-right">
                                        R$ {{ number_format($material->price, 2, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-900 text-right">
                                        R$ {{ number_format($material->pivot->subtotal, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-neutral-50">
                                <th scope="row" colspan="3" class="pl-3 pr-3 py-3.5 text-right text-sm font-semibold text-neutral-900">Total</th>
                                <td class="pl-3 pr-3 py-3.5 text-right text-sm font-semibold text-neutral-900">
                                    R$ {{ number_format($order->total, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex items-center justify-end gap-x-3">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                Voltar
            </a>

            @if(in_array($order->status, ['new', 'changes_requested']))
                <a href="{{ route('orders.edit', $order) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                    <svg class="h-5 w-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Pedido
                </a>
            @endif

            @if($order->status === 'new')
                <button type="button"
                        wire:click="sendToReview"
                        wire:confirm="Tem certeza que deseja enviar este pedido para revisão?"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Enviar para Revisão
                </button>
            @endif
        </div>
    </div>
</div>
