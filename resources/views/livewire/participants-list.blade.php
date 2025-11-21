<div>
    <x-card title="👥 Participantes da Mega-Sena">
        <x-slot name="action">
            <x-badge color="green" :text="$totalParticipants . ' participante(s)'" round lg />
        </x-slot>

        @if ($totalParticipants === 0)
            <div class="py-12 text-center">
                <div class="mb-4 text-6xl">🎲</div>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Nenhum participante ainda. Seja o primeiro a escolher seus números!
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($participants as $choice)
                    <div
                        class="rounded-lg bg-gradient-to-r from-gray-50 to-gray-100 p-5 shadow-sm transition-shadow duration-200 hover:shadow-md dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-start gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-green-600 text-lg font-bold text-white shadow-lg">
                                    {{ $this->getInitials($choice->user->name) }}
                                </div>
                            </div>

                            <!-- Informações do Participante -->
                            <div class="flex-1">
                                <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                            {{ $choice->user->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $choice->user->email }}
                                        </p>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $choice->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Números Escolhidos -->
                                <div>
                                    <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                        Números escolhidos:
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($choice->sorted_numbers as $number)
                                            <div
                                                class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 text-sm font-bold text-white shadow-md">
                                                {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Badge de Status -->
                                <div class="mt-3">
                                    <x-badge color="green" text="✓ Confirmado" sm />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Estatísticas -->
            <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                <div class="grid grid-cols-1 gap-4 text-center sm:grid-cols-3">
                    <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ $totalParticipants }}
                        </div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Participantes
                        </div>
                    </div>
                    <div class="rounded-lg bg-purple-50 p-4 dark:bg-purple-900/20">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $totalParticipants * 6 }}
                        </div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Números Escolhidos
                        </div>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            60
                        </div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Números Disponíveis
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-card>

    <div class="mt-6 text-center">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 font-semibold text-green-600 transition-colors hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Voltar ao Dashboard
        </a>
    </div>
</div>
