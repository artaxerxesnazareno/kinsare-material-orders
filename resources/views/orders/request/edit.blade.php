<x-app-layout>
    <x-slot name="header">
        <div class="bg-white shadow-sm border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center">
                            <a href="{{ route('orders.show', $order) }}"
                               class="mr-4 rounded-lg p-2 text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-all">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-3xl font-display font-bold text-neutral-900 sm:tracking-tight">
                                    Editar Pedido
                                </h1>
                                <p class="mt-2 text-lg text-neutral-600">
                                    Atualize os materiais do seu pedido
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <livewire:request.order.edit-order :order="$order"/>
    </div>
</x-app-layout>
