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
