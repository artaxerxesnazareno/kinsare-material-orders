<x-app-layout>
    @if(Auth::user()->profile === 'requester')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                    <!-- Total de Pedidos -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Total de Pedidos</div>
                        <div class="text-3xl font-bold text-indigo-600">
                            {{ Auth::user()->requester->orders()->count() }}
                        </div>
                    </div>

                    <!-- Pedidos Novos -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Novos</div>
                        <div class="text-3xl font-bold text-blue-600">
                            {{ Auth::user()->requester->orders()->where('status', 'new')->count() }}
                        </div>
                    </div>

                    <!-- Pedidos Aprovados -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Aprovados</div>
                        <div class="text-3xl font-bold text-green-600">
                            {{ Auth::user()->requester->orders()->where('status', 'approved')->count() }}
                        </div>
                    </div>

                    <!-- Pedidos em Revisão -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Em Revisão</div>
                        <div class="text-3xl font-bold text-yellow-600">
                            {{ Auth::user()->requester->orders()->where('status', 'review')->count() }}
                        </div>
                    </div>

                    <!-- Alterações Solicitadas -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Alterações Solicitadas</div>
                        <div class="text-3xl font-bold text-orange-600">
                            {{ Auth::user()->requester->orders()->where('status', 'changes_requested')->count() }}
                        </div>
                    </div>

                    <!-- Pedidos Rejeitados -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="text-gray-900 text-xl font-semibold mb-2">Rejeitados</div>
                        <div class="text-3xl font-bold text-red-600">
                            {{ Auth::user()->requester->orders()->where('status', 'rejected')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
