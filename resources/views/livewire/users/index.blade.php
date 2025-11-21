<div class="min-h-screen bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Gerenciar Usuários</h1>
            <p class="mt-2 text-gray-600">Administre os usuários e suas permissões</p>
        </div>

        <!-- Alerta Admin -->
        @if (auth()->user()->is_admin)
            <div class="mb-6 rounded-xl border-l-4 border-blue-500 bg-blue-50 p-4">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-6 w-6 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800">Você é um administrador</p>
                        <p class="text-sm text-blue-700">Você pode gerenciar permissões de outros usuários e criar novos
                            usuários.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Card Principal -->
        <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
            <!-- Botão Criar Usuário -->
            <div class="mb-6">
                <livewire:users.create @created="$refresh" />
            </div>

            <!-- Barra de Busca e Filtros -->
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="🔍 Buscar por nome ou email..."
                        class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div>
                    <select wire:model.live="quantity"
                        class="rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                        <option value="5">5 por página</option>
                        <option value="15">15 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                    </select>
                </div>
            </div>

            <!-- Tabela Responsiva -->
            <div class="overflow-hidden rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    ID
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Usuário
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Permissão
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Criado em
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($this->rows as $row)
                                <tr class="transition-colors hover:bg-gray-50">
                                    <!-- ID -->
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        #{{ $row->id }}
                                    </td>

                                    <!-- Usuário (Avatar + Info) -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-sm font-bold text-white">
                                                {{ strtoupper(substr($row->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $row->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $row->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Permissão -->
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if ($row->is_admin)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                        </path>
                                                    </svg>
                                                    Admin
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                    Usuário
                                                </span>
                                            @endif

                                            @if (auth()->user()->is_admin)
                                                <button wire:click="toggleAdmin({{ $row->id }})"
                                                    class="{{ $row->is_admin ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} rounded-full p-1.5 transition-colors"
                                                    title="{{ $row->is_admin ? 'Remover Admin' : 'Tornar Admin' }}">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($row->is_admin)
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                            </path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                            </path>
                                                        @endif
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Criado em -->
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    <!-- Ações -->
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})"
                                                class="rounded-full bg-blue-100 p-2 text-blue-600 transition-colors hover:bg-blue-200"
                                                title="Editar usuário">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <livewire:users.delete :user="$row" :key="uniqid('', true)"
                                                @deleted="$refresh" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-600">Nenhum usuário
                                                    encontrado</p>
                                                <p class="text-sm text-gray-500">Tente ajustar seus filtros de busca
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginação -->
            @if ($this->rows->hasPages())
                <div class="mt-6">
                    {{ $this->rows->links() }}
                </div>
            @endif
        </div>

        <!-- Link de Voltar -->
        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-gray-600 transition-colors hover:text-green-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar ao Dashboard
            </a>
        </div>
    </div>

    <livewire:users.update @updated="$refresh" />
</div>
