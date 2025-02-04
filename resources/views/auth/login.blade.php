<x-guest-layout>
    <div class="min-h-[calc(100vh-8rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white rounded-xl shadow-lg p-8">
            <!-- Logo e Cabeçalho -->
            <div class="text-center">
                <div class="flex justify-center">
                    <div class="h-16 w-16 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="h-10 w-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-6 text-3xl font-display font-bold text-neutral-900">
                    Bem-vindo de volta
                </h2>
                <p class="mt-2 text-sm text-neutral-600">
                    Sistema de Gestão de Pedidos de Materiais
                </p>
            </div>

            <!-- Mensagens de Status -->
            @if (session('status'))
                <div class="rounded-lg bg-success-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-success-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-success-800">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulário de Login -->
            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf

                <div class="space-y-5 rounded-md">
                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label flex items-center justify-between">
                            <span>Email</span>
                            @error('email')
                                <span class="text-sm text-danger-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="seu@email.com"
                                   class="form-input pl-10 @error('email') border-danger-300 text-danger-900 placeholder-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror">
                        </div>
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="form-label flex items-center justify-between">
                            <span>Senha</span>
                            @error('password')
                                <span class="text-sm text-danger-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="form-input pl-10 @error('password') border-danger-300 text-danger-900 placeholder-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="form-checkbox">
                        <label for="remember_me" class="ml-2 block text-sm text-neutral-600">
                            Lembrar-me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full group relative flex justify-center py-3">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-primary-300 group-hover:text-primary-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </span>
                        Entrar na sua conta
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-neutral-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-neutral-500">Ou</span>
                        </div>
                    </div>


                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
