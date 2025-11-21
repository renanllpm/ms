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

<body class="font-sans antialiased bg-gray-50" x-data="{ name: @js(auth()->user()->name) }" x-on:name-updated.window="name = $event.detail.name">
    
    <x-dialog />
    <x-toast />

    <!-- Top Navigation Bar -->
    <nav class="megasena-gradient text-white topbar-shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 sm:space-x-3">
                        <span class="text-2xl sm:text-3xl">🍀</span>
                        <span class="text-base sm:text-xl font-bold tracking-tight">Mega-Sena</span>
                    </a>
                </div>

                @if (auth()->user()->is_admin)
                    <!-- Desktop Navigation Links -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-3 xl:px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            🏠 <span class="hidden xl:inline">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.participants') }}" class="px-3 xl:px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.participants') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            💰 <span class="hidden xl:inline">Participantes</span>
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="px-3 xl:px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.statistics') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            📊 <span class="hidden xl:inline">Estatísticas</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="px-3 xl:px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            ⚙️ <span class="hidden xl:inline">Configurações</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="px-3 xl:px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('users.index') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            👥 <span class="hidden xl:inline">Usuários</span>
                        </a>
                    </div>
                @endif

                <!-- Right Side -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- User Dropdown - Desktop -->
                    <div class="hidden sm:block relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            <span class="font-medium text-sm" x-text="name"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50"
                             style="display: none;">
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                👤 Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t">
                                    🚪 Sair
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    @if (auth()->user()->is_admin)
                        <div class="lg:hidden" x-data="{ mobileOpen: false }">
                            <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-lg hover:bg-white/10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
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
                                 class="absolute top-16 left-0 right-0 bg-white shadow-lg z-50"
                                 style="display: none;">
                                <div class="py-2 px-4 border-b bg-gray-50">
                                    <p class="text-sm font-semibold text-gray-700" x-text="name"></p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-green-50 border-l-4 border-green-600' : '' }}">
                                    <span class="mr-3">🏠</span> Dashboard
                                </a>
                                <a href="{{ route('admin.participants') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.participants') ? 'bg-green-50 border-l-4 border-green-600' : '' }}">
                                    <span class="mr-3">💰</span> Participantes
                                </a>
                                <a href="{{ route('admin.statistics') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.statistics') ? 'bg-green-50 border-l-4 border-green-600' : '' }}">
                                    <span class="mr-3">📊</span> Estatísticas
                                </a>
                                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.settings') ? 'bg-green-50 border-l-4 border-green-600' : '' }}">
                                    <span class="mr-3">⚙️</span> Configurações
                                </a>
                                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('users.index') ? 'bg-green-50 border-l-4 border-green-600' : '' }}">
                                    <span class="mr-3">👥</span> Usuários
                                </a>
                                <div class="border-t">
                                    <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                        <span class="mr-3">👤</span> Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 text-left">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>

</html>
