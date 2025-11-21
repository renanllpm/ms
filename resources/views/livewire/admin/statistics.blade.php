<div>
    <x-card title="📊 Estatísticas da Votação - Mega-Sena">
        <x-slot name="action">
            <div class="flex items-center gap-3">
                <x-badge color="blue" :text="$totalVotes . ' voto(s)'" round lg />
                <x-button wire:click="refresh" color="slate" icon="arrow-path" sm>
                    Atualizar
                </x-button>
            </div>
        </x-slot>

        @if ($totalVotes === 0)
            <div class="py-12 text-center">
                <div class="mb-4 text-6xl">📊</div>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Nenhum voto registrado ainda. Aguardando participantes...
                </p>
            </div>
        @else
            <!-- Resumo Geral -->
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg">
                    <div class="mb-1 text-sm font-semibold opacity-90">Total de Votos</div>
                    <div class="text-4xl font-bold">{{ $totalVotes }}</div>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-lg">
                    <div class="mb-1 text-sm font-semibold opacity-90">Números Votados</div>
                    <div class="text-4xl font-bold">{{ $totalNumbers }}</div>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white shadow-lg">
                    <div class="mb-1 text-sm font-semibold opacity-90">Média por Voto</div>
                    <div class="text-4xl font-bold">6</div>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white shadow-lg">
                    <div class="mb-1 text-sm font-semibold opacity-90">Números Disponíveis</div>
                    <div class="text-4xl font-bold">60</div>
                </div>
            </div>

            <!-- Top 10 Números Mais Escolhidos -->
            <div class="mb-8">
                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span>🔥</span> Top 10 - Números Mais Votados
                </h3>
                <div
                    class="rounded-lg bg-gradient-to-r from-red-50 to-orange-50 p-6 dark:from-red-900/20 dark:to-orange-900/20">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($topNumbers as $number => $count)
                            <div
                                class="flex items-center justify-between rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-red-600 text-lg font-bold text-white shadow-lg">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Número</div>
                                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                            {{ $number }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $count }}x
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $this->getPercentage($count) }}%
                                    </div>
                                    <div class="mt-2 h-2 w-32 rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-red-500 to-orange-500 transition-all duration-300"
                                            style="width: {{ $this->getPercentage($count) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 10 Números Menos Escolhidos -->
            <div class="mb-8">
                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span>❄️</span> Top 10 - Números Menos Votados
                </h3>
                <div
                    class="rounded-lg bg-gradient-to-r from-blue-50 to-cyan-50 p-6 dark:from-blue-900/20 dark:to-cyan-900/20">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($leastNumbers as $number => $count)
                            <div
                                class="flex items-center justify-between rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-lg font-bold text-white shadow-lg">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Número</div>
                                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                            {{ $number }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $count }}x</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $this->getPercentage($count) }}%
                                    </div>
                                    <div class="mt-2 h-2 w-32 rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 transition-all duration-300"
                                            style="width: {{ $this->getPercentage($count) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mapa de Calor de Todos os Números -->
            <div class="mb-8">
                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span>🗺️</span> Mapa de Calor - Todos os Números (1-60)
                </h3>
                <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                    <div class="grid grid-cols-6 gap-2 sm:grid-cols-10">
                        @for ($i = 1; $i <= 60; $i++)
                            @php
                                $count = $numberFrequency[$i] ?? 0;
                                $percentage = $this->getPercentage($count);
                                $color = $this->getNumberColor($count);

                                $bgClass = match ($color) {
                                    'red' => 'bg-gradient-to-br from-red-500 to-red-600',
                                    'orange' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                                    'yellow' => 'bg-gradient-to-br from-yellow-400 to-yellow-500',
                                    'green' => 'bg-gradient-to-br from-green-500 to-green-600',
                                    default => 'bg-gradient-to-br from-gray-300 to-gray-400',
                                };
                            @endphp
                            <div class="group relative">
                                <div
                                    class="{{ $bgClass }} flex h-16 w-full cursor-pointer flex-col items-center justify-center rounded-lg text-sm font-bold text-white shadow transition-transform hover:scale-105">
                                    <div class="text-base">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs opacity-90">{{ $count }}x</div>
                                </div>
                                <!-- Tooltip -->
                                <div
                                    class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 transform whitespace-nowrap rounded-lg bg-gray-900 px-3 py-2 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                                    Número {{ $i }}: {{ $count }}x ({{ $percentage }}%)
                                    <div
                                        class="absolute left-1/2 top-full -translate-x-1/2 transform border-4 border-transparent border-t-gray-900">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Legenda -->
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded bg-gradient-to-br from-red-500 to-red-600"></div>
                            <span class="text-gray-700 dark:text-gray-300">≥ 50% (Muito escolhido)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded bg-gradient-to-br from-orange-500 to-orange-600"></div>
                            <span class="text-gray-700 dark:text-gray-300">30-49%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded bg-gradient-to-br from-yellow-400 to-yellow-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">15-29%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded bg-gradient-to-br from-green-500 to-green-600"></div>
                            <span class="text-gray-700 dark:text-gray-300">5-14%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded bg-gradient-to-br from-gray-300 to-gray-400"></div>
                            <span class="text-gray-700 dark:text-gray-300">
                                < 5% (Pouco escolhido)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimos Votos -->
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span>🕐</span> Últimos 5 Votos
                </h3>
                <div class="space-y-3">
                    @foreach ($recentVotes as $vote)
                        <div
                            class="rounded-lg bg-white p-4 shadow-sm transition-shadow hover:shadow-md dark:bg-gray-800">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-sm font-bold text-white">
                                        {{ strtoupper(substr($vote->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $vote->name }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $vote->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($vote->paid)
                                        <span
                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-600">
                                            ✓ Contribuiu
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-600">
                                            A Contribuir
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($vote->sorted_numbers as $number)
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 text-sm font-bold text-white shadow">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Loading State -->
        <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <div class="flex items-center space-x-3">
                    <svg class="h-8 w-8 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Atualizando...</span>
                </div>
            </div>
        </div>
    </x-card>

    <div class="mt-6 text-center">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 font-semibold text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar ao Dashboard
        </a>
    </div>
</div>
