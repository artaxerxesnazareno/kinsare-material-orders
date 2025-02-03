<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-neutral-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kinsare Material Orders') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lexend:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Cabeçalho Minimalista -->
            <header class="bg-white shadow-sm">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <a href="/" class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span class="text-xl font-display font-bold text-neutral-900">
                                    {{ config('app.name', 'Kinsare') }}
                                </span>
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login') && !request()->routeIs('login'))
                                <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-500 hover:text-neutral-900 transition-colors">
                                    Entrar
                                </a>
                            @endif

                            @if (Route::has('register') && !request()->routeIs('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                    Criar conta
                                </a>
                            @endif
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Conteúdo Principal -->
            <main class="flex-grow bg-neutral-50">
                {{ $slot }}
            </main>

            <!-- Rodapé Minimalista -->
            <footer class="bg-white border-t border-neutral-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-neutral-500 text-sm">
                            © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="#" class="text-neutral-400 hover:text-neutral-500 transition-colors">
                                Termos
                            </a>
                            <a href="#" class="text-neutral-400 hover:text-neutral-500 transition-colors">
                                Privacidade
                            </a>
                            <a href="#" class="text-neutral-400 hover:text-neutral-500 transition-colors">
                                Contato
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
