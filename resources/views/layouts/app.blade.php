<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .megasena-gradient {
            background: linear-gradient(135deg, #00983F 0%, #00A94F 50%, #38C172 100%);
        }

        .topbar-shadow {
            box-shadow: 0 2px 8px rgba(0, 152, 63, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased" x-data="{ name: @js(auth()->user()->name) }"
    x-on:name-updated.window="name = $event.detail.name">

    <x-dialog />
    <x-toast />

    <!-- Top Navigation Bar -->
    <nav class="megasena-gradient topbar-shadow sticky top-0 z-50 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 sm:space-x-3">
                        <span class="text-2xl sm:text-3xl">🍀</span>
                        <span class="text-base font-bold tracking-tight sm:text-xl">Mega-Sena</span>
                    </a>
                </div>

                @if (auth()->user()->is_admin)
                    <!-- Desktop Navigation Links -->
                    <div class="hidden items-center space-x-1 lg:flex">
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors xl:px-4">
                            🏠 <span class="hidden xl:inline">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.participants') }}"
                            class="{{ request()->routeIs('admin.participants') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors xl:px-4">
                            💰 <span class="hidden xl:inline">Participantes</span>
                        </a>
                        <a href="{{ route('admin.statistics') }}"
                            class="{{ request()->routeIs('admin.statistics') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors xl:px-4">
                            📊 <span class="hidden xl:inline">Estatísticas</span>
                        </a>
                        <a href="{{ route('admin.settings') }}"
                            class="{{ request()->routeIs('admin.settings') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors xl:px-4">
                            ⚙️ <span class="hidden xl:inline">Configurações</span>
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="{{ request()->routeIs('users.index') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors xl:px-4">
                            👥 <span class="hidden xl:inline">Usuários</span>
                        </a>
                    </div>
                @endif

                <!-- Right Side -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- User Dropdown - Desktop -->
                    <div class="relative hidden sm:block" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 rounded-lg px-3 py-2 transition-colors hover:bg-white/10">
                            <span class="text-sm font-medium" x-text="name"></span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 rounded-lg bg-white py-1 shadow-xl"
                            style="display: none;">
                            <a href="{{ route('user.profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                👤 Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full border-t px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                    🚪 Sair
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    @if (auth()->user()->is_admin)
                        <div class="lg:hidden" x-data="{ mobileOpen: false }">
                            <button @click="mobileOpen = !mobileOpen" class="rounded-lg p-2 hover:bg-white/10">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <!-- Mobile Menu -->
                            <div x-show="mobileOpen" @click.away="mobileOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 right-0 top-16 z-50 bg-white shadow-lg" style="display: none;">
                                <div class="border-b bg-gray-50 px-4 py-2">
                                    <p class="text-sm font-semibold text-gray-700" x-text="name"></p>
                                </div>
                                <a href="{{ route('dashboard') }}"
                                    class="{{ request()->routeIs('dashboard') ? 'bg-green-50 border-l-4 border-green-600' : '' }} flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="mr-3">🏠</span> Dashboard
                                </a>
                                <a href="{{ route('admin.participants') }}"
                                    class="{{ request()->routeIs('admin.participants') ? 'bg-green-50 border-l-4 border-green-600' : '' }} flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="mr-3">💰</span> Participantes
                                </a>
                                <a href="{{ route('admin.statistics') }}"
                                    class="{{ request()->routeIs('admin.statistics') ? 'bg-green-50 border-l-4 border-green-600' : '' }} flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="mr-3">📊</span> Estatísticas
                                </a>
                                <a href="{{ route('admin.settings') }}"
                                    class="{{ request()->routeIs('admin.settings') ? 'bg-green-50 border-l-4 border-green-600' : '' }} flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="mr-3">⚙️</span> Configurações
                                </a>
                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.index') ? 'bg-green-50 border-l-4 border-green-600' : '' }} flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="mr-3">👥</span> Usuários
                                </a>
                                <div class="border-t">
                                    <a href="{{ route('user.profile') }}"
                                        class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                        <span class="mr-3">👤</span> Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center px-4 py-3 text-left text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-3">🚪</span> Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4 sm:py-6 lg:py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>

</html>
