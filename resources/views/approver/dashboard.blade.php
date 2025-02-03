<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight">
                    Dashboard do Aprovador
                </h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Gerencie os pedidos que necessitam da sua aprovação
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <livewire:approver.dashboard />
    </div>
</x-app-layout>
