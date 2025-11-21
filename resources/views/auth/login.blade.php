<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Votação Mega-Sena</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .megasena-gradient {
            background: linear-gradient(135deg, #00983F 0%, #00A94F 50%, #38C172 100%);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo/Header -->
            <div class="mb-8 text-center">
                <div
                    class="megasena-gradient mb-4 inline-flex h-20 w-20 items-center justify-center rounded-full shadow-lg">
                    <span class="text-4xl">🍀</span>
                </div>
                <h1
                    class="mb-2 bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-3xl font-bold text-transparent">
                    Votação Mega-Sena
                </h1>
                <p class="text-gray-600">Acesso Administrativo</p>
            </div>

            <!-- Card de Login -->
            <div class="rounded-3xl bg-white p-8 shadow-2xl">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                            E-mail
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', '') }}" required
                            autofocus autocomplete="username"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">
                            Senha
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">
                            Manter conectado
                        </label>
                    </div>

                    <!-- Erro de autenticação -->
                    @if ($errors->has('email') || $errors->has('password'))
                        <div class="rounded-xl border-2 border-red-200 bg-red-50 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-red-800">
                                        Credenciais inválidas
                                    </p>
                                    <p class="mt-1 text-xs text-red-700">
                                        Verifique seu e-mail e senha e tente novamente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Botão -->
                    <button type="submit"
                        class="megasena-gradient w-full transform rounded-xl py-4 font-bold text-white transition-all duration-200 hover:scale-105 hover:shadow-xl">
                        Entrar
                    </button>
                </form>
            </div>

            <!-- Link de volta -->
            <div class="mt-6 text-center">
                <a href="/"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 transition-colors hover:text-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para votação
                </a>
            </div>
        </div>
    </div>
</body>

</html>
