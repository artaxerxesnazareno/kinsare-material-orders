<!-- Navegação Principal -->
<nav x-data="{ open: false }" class="bg-white border-b border-neutral-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-display font-bold text-primary-600 hover:text-primary-700 transition-colors">
                        {{ config('app.name', 'Kinsare') }}
                    </a>
                </div>

                <!-- Links de Navegação -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    @if(Auth::user()->profile === 'requester')
                        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Meus Pedidos
                        </a>
                        <a href="{{ route('orders.create') }}" class="nav-link {{ request()->routeIs('orders.create') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Novo Pedido
                        </a>
                    @endif

                    @if(Auth::user()->profile === 'approver')
                        <a href="{{ route('approver.dashboard') }}" class="nav-link {{ request()->routeIs('approver.dashboard') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Pedidos
                        </a>
                    @endif

                    @if(Auth::user()->profile === 'admin')
                        <a href="{{ route('materials.index') }}" class="nav-link {{ request()->routeIs('materials.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Materiais
                        </a>
                        <a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Grupos
                        </a>
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Usuários
                        </a>

                    @endif
                </div>
            </div>

            <!-- Menu do Usuário -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                    <div>
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-neutral-500 hover:text-neutral-700 focus:outline-none transition duration-150 ease-in-out">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-2">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                            </div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right">
                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">


                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 transition-colors">
                                    <svg class="mr-3 h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão do Menu Mobile -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none focus:bg-neutral-100 focus:text-neutral-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            @if(Auth::user()->profile === 'requester')
                <a href="{{ route('orders.index') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('orders.*') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Meus Pedidos
                </a>
                <a href="{{ route('orders.create') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('orders.create') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Pedido
                </a>
            @endif

            @if(Auth::user()->profile === 'approver')
                <a href="{{ route('orders.pending') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('orders.pending') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pedidos Pendentes
                </a>
            @endif

            @if(Auth::user()->profile === 'admin')
                <a href="{{ route('materials.index') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('materials.*') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Materiais
                </a>
                <a href="{{ route('groups.index') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('groups.*') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Grupos
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('users.*') ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-transparent text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300' }} text-base font-medium transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Usuários
                </a>
            @endif
        </div>

        <!-- Menu Mobile do Usuário -->
        <div class="pt-4 pb-1 border-t border-neutral-200">
            <div class="flex items-center px-4">
                <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-base text-neutral-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-neutral-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-600 hover:text-neutral-800 hover:bg-neutral-50 hover:border-neutral-300 transition-colors">
                        <svg class="mr-3 h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
