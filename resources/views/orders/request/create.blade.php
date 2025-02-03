<x-app-layout>
    <x-slot name="header">
        <div class="bg-white shadow-sm border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-3xl font-display font-bold text-neutral-900 sm:tracking-tight">
                            Novo Pedido
                        </h1>
                        <p class="mt-2 text-lg text-neutral-600">
                            Crie um novo pedido de materiais
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <livewire:request.order.create-order/>
    </div>
</x-app-layout>
