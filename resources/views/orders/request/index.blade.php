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
                        <button type="button"
                                wire:click="$dispatch('filterByStatus', { status: 'new' })"
                                class="mr-3 inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 transition-all duration-200">
                            <svg class="h-5 w-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Pedidos Novos
                        </button>
                        <a href="{{ route('orders.create') }}"
                           class="inline-flex items-center gap-x-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all duration-200">
                            Novo Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <livewire:request.order.orders-list/>
    </div>
</x-app-layout>
