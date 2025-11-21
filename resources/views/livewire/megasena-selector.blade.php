<div>
    <x-card title="🍀 Mega-Sena - Escolha seus Números da Sorte">
        <x-slot name="action">
            @if ($hasChosen)
                <x-badge color="green" text="Escolha Confirmada" round lg />
            @else
                <x-badge :color="count($selectedNumbers) === 6 ? 'green' : 'gray'" :text="count($selectedNumbers) . '/6 números'" round lg />
            @endif
        </x-slot>

        @if ($hasChosen)
            <!-- Números Salvos -->
            <div class="mb-6">
                <x-alert color="green" text="✅ Você já fez sua escolha! Boa sorte no sorteio!" />

                <div class="mt-4">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Seus números escolhidos:
                    </h3>
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach ($savedNumbers as $number)
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 text-xl font-bold text-white shadow-lg">
                                {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    <p>Escolha realizada em {{ auth()->user()->megasenaChoice->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        @else
            <!-- Seleção de Números -->
            <div class="mb-6">
                <x-alert color="blue" text="⚠️ Atenção: Você só pode escolher uma vez! Escolha com cuidado." />

                <!-- Números Selecionados Preview -->
                @if (count($selectedNumbers) > 0)
                    <div class="mt-4 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Números Selecionados:
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($selectedNumbers as $number)
                                <span
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white shadow">
                                    {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botões de Ação -->
            <div class="mb-6 flex flex-wrap justify-center gap-3">
                <x-button wire:click="generateRandom" color="purple" icon="arrow-path" wire:loading.attr="disabled">
                    Gerar Aleatório
                </x-button>

                <x-button wire:click="clearSelection" color="slate" icon="x-mark" wire:loading.attr="disabled">
                    Limpar
                </x-button>
            </div>

            <!-- Grid de Números (1-60) -->
            <div class="mb-6">
                <h3 class="mb-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Clique nos números para selecionar:
                </h3>
                <div class="grid grid-cols-6 gap-2 sm:grid-cols-8 md:grid-cols-10">
                    @for ($i = 1; $i <= 60; $i++)
                        <button type="button" wire:click="toggleNumber({{ $i }})"
                            @class([
                                'flex items-center justify-center w-full h-12 rounded-lg font-bold text-sm transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2',
                                'bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg ring-2 ring-green-400' => $this->isSelected(
                                    $i),
                                'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' => !$this->isSelected(
                                    $i),
                            ]) wire:loading.attr="disabled">
                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Botão Confirmar -->
            <div class="flex justify-center">
                <x-button wire:click="saveChoice" color="green" size="lg" icon="check-circle" :disabled="!$this->canSave()"
                    wire:loading.attr="disabled">
                    Confirmar Escolha
                </x-button>
            </div>

            @if (count($selectedNumbers) < 6 && count($selectedNumbers) > 0)
                <p class="mt-3 text-center text-sm text-gray-600 dark:text-gray-400">
                    Selecione mais {{ 6 - count($selectedNumbers) }} número(s)
                </p>
            @endif
        @endif

        <!-- Loading State -->
        <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <div class="flex items-center space-x-3">
                    <svg class="h-8 w-8 animate-spin text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Processando...</span>
                </div>
            </div>
        </div>
    </x-card>

    <div class="mt-6 text-center text-gray-600 dark:text-gray-400">
        <p class="text-lg">💚 Boa sorte na Mega-Sena!</p>
    </div>
</div>
