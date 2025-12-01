<div>
    <!-- Botão para abrir modal -->
    <div class="mb-4">
        <button wire:click="$toggle('modal')"
            class="megasena-gradient inline-flex items-center gap-2 rounded-xl px-6 py-3 font-semibold text-white transition-all hover:scale-105 hover:shadow-lg">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Criar Nova Votação
        </button>
    </div>

    <!-- Modal -->
    <x-modal wire title="📝 Criar Nova Votação">
        <div class="flex items-center justify-center p-4">
            <div class="w-full">
                <!-- Formulário -->
                <form wire:submit="createParticipant" class="space-y-6">
                    <!-- Dados Pessoais -->
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Nome *</label>
                                <input type="text" wire:model="name" placeholder="Nome completo"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                                @error('name')
                                    <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Telefone *</label>
                                <input type="tel" wire:model="phone" placeholder="(11) 99999-9999"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                                @error('phone')
                                    <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Valor da Aposta -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Valor da Aposta (R$) *</label>
                        <input type="number" wire:model="amount" placeholder="5.00" step="0.01" min="0.01"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                        @error('amount')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Checkbox de Abstenção -->
                    <div class="flex items-center gap-3 rounded-lg border-2 border-yellow-200 bg-yellow-50 p-4">
                        <input type="checkbox" wire:model.live="abstained" id="abstained" class="rounded">
                        <label for="abstained" class="text-sm font-medium text-yellow-900">
                            Registrar como abstenção
                        </label>
                    </div>

                    <!-- Números -->
                    @if (!$abstained)
                        <div>
                            <div class="mb-4 flex items-center justify-between">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Escolha {{ count($selectedNumbers) }}/{{ $this->numbersToPickProperty }} números *
                                </label>
                                <div class="flex gap-2">
                                    <button type="button" wire:click="generateRandom"
                                        class="rounded-lg bg-purple-600 px-3 py-1 text-sm font-medium text-white transition-colors hover:bg-purple-700">
                                        🎲 Surpresinha
                                    </button>
                                    <button type="button" wire:click="clearSelection"
                                        class="rounded-lg bg-gray-600 px-3 py-1 text-sm font-medium text-white transition-colors hover:bg-gray-700">
                                        ✖️ Limpar
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-6 gap-2 md:grid-cols-10">
                                @for ($i = $this->minNumberProperty; $i <= $this->maxNumberProperty; $i++)
                                    <button type="button" wire:click="toggleNumber({{ $i }})"
                                        class="{{ $this->isSelected($i) ? 'megasena-gradient text-white shadow-lg' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} flex aspect-square items-center justify-center rounded-lg font-bold transition-all">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </button>
                                @endfor
                            </div>
                            @error('selectedNumbers')
                                <span class="mt-2 text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="rounded-lg border-2 border-yellow-200 bg-yellow-50 p-4 text-center">
                            <svg class="mx-auto mb-2 h-8 w-8 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4v2m0 4v2M4.22 4.22a9 9 0 1112.56 12.56M4.22 4.22a9 9 0 0012.56 12.56" />
                            </svg>
                            <p class="font-semibold text-yellow-900">Abstenção registrada</p>
                            <p class="text-sm text-yellow-800">Nenhum número será selecionado</p>
                        </div>
                    @endif

                    <!-- Status de Pagamento -->
                    <div class="flex items-center gap-3 rounded-lg bg-blue-50 p-4">
                        <input type="checkbox" wire:model="paid" id="paid" class="rounded">
                        <label for="paid" class="text-sm font-medium text-gray-700">
                            Marcar como já pago
                        </label>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-3 border-t border-gray-200 pt-4">
                        <button type="button" wire:click="$toggle('modal')"
                            class="flex-1 rounded-lg bg-gray-100 py-3 font-semibold text-gray-700 transition-colors hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="megasena-gradient flex-1 rounded-lg py-3 font-semibold text-white transition-all hover:shadow-lg disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                @if ($abstained)
                                    ✖️ Registrar Abstenção
                                @else
                                    ✅ Criar Votação
                                @endif
                            </span>
                            <span wire:loading>Processando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>
