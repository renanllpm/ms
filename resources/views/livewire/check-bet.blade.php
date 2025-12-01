<div class="flex min-h-screen items-center justify-center p-4">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1
                class="mb-2 bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-4xl font-bold text-transparent md:text-5xl">
                Consultar Aposta
            </h1>
            <p class="text-gray-600">Digite seu código de acesso para verificar seus números</p>
        </div>

        <!-- Formulário de Busca -->
        <div class="mb-6 rounded-3xl bg-white p-8 shadow-2xl">
            <form wire:submit="search" class="space-y-6">
                <div>
                    <label for="accessCode" class="mb-2 block text-sm font-semibold text-gray-700">
                        Código de Acesso
                    </label>
                    <input type="text" id="accessCode" wire:model="accessCode" placeholder="Ex: ABC12345"
                        maxlength="8"
                        class="w-full rounded-2xl border-2 border-gray-200 px-6 py-4 text-center font-mono text-lg font-bold uppercase tracking-widest transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100">
                    @error('accessCode')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 transform rounded-2xl bg-gradient-to-r from-green-600 to-green-700 py-4 font-bold text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-green-700 hover:to-green-800 hover:shadow-xl">
                        Consultar
                    </button>

                    @if ($searched)
                        <button type="button" wire:click="clear"
                            class="rounded-2xl bg-gray-100 px-6 font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                            Limpar
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Resultado -->
        @if ($searched && $participant)
            <div class="animate-fadeIn space-y-6 rounded-3xl bg-white p-8 shadow-2xl">
                <!-- Status de Pagamento -->
                <div class="flex items-center justify-between border-b border-gray-200 pb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $participant->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Código: {{ $participant->access_code }}</p>
                    </div>
                    <div class="text-right">
                        @if ($participant->paid)
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 font-semibold text-green-800">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Contribuiu
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-4 py-2 font-semibold text-yellow-800">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                A Contribuir
                            </span>
                        @endif
                        <p class="mt-2 text-2xl font-bold text-green-600">
                            R$ {{ number_format($participant->amount, 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Telefone (se não preenchido) -->
                @if (!$participant->phone)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">Adicionar Telefone</h3>

                        <div class="mb-4 rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm text-blue-800">
                                ℹ️ Se você deseja receber sua votação via WhatsApp, adicione seu telefone aqui.
                            </p>
                        </div>

                        <div wire:loading.remove>
                            <div class="space-y-3">
                                <div>
                                    <input type="tel" wire:model="phone" placeholder="(11) 99999-9999"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                                    @error('phone')
                                        <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button wire:click="updatePhone"
                                    class="w-full transform rounded-lg bg-green-600 py-2 font-semibold text-white transition-all hover:scale-105 hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                                    wire:loading.attr="disabled" :disabled="!phone">
                                    <span wire:loading.remove>
                                        📱 Adicionar Telefone
                                    </span>
                                    <span wire:loading>
                                        Atualizando...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div wire:loading class="text-center">
                            <div class="inline-block">
                                <div
                                    class="h-8 w-8 animate-spin rounded-full border-4 border-green-300 border-t-green-600">
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600">Atualizando telefone...</p>
                        </div>
                    </div>
                @endif

                <!-- Comprovante de Pagamento -->
                <div class="border-t border-gray-200 pt-6">
                    @if ($participant->payment_proof)
                        <div class="mb-4 rounded-xl border-2 border-green-200 bg-green-50 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                        <path
                                            d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800">✓ Comprovante enviado</p>
                                        <p class="text-xs text-green-700">Seu comprovante foi recebido com sucesso</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($participant->payment_proof) }}" target="_blank"
                                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Ver comprovante
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="mb-4 flex items-center justify-between text-lg font-semibold text-gray-800">
                                <span class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Comprovante de Contribuição
                                </span>
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                                    Opcional
                                </span>
                            </h3>

                            <div class="mb-4 rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                                <p class="text-sm text-blue-800">
                                    ℹ️ Se você esqueceu de enviar seu comprovante, você pode enviá-lo aqui. O envio é
                                    opcional mas ajuda na organização.
                                </p>
                            </div>

                            <div wire:loading.remove>
                                <div
                                    class="rounded-xl border-2 border-dashed border-gray-300 p-6 text-center transition-colors hover:border-green-500">
                                    <input type="file" wire:model="paymentProof" accept="image/*,.pdf" class="hidden"
                                        id="fileUpload">
                                    <label for="fileUpload" class="cursor-pointer">
                                        <div class="mb-3 text-4xl">📎</div>
                                        <p class="mb-2 text-gray-600">Clique para enviar o comprovante</p>
                                        <p class="text-xs text-gray-500">JPG, PNG ou PDF (Máx: 5MB)</p>
                                    </label>
                                    @if ($paymentProof)
                                        <p class="mt-3 text-sm font-medium text-green-600">✓ Arquivo selecionado:
                                            {{ $paymentProof->getClientOriginalName() }}</p>
                                    @endif
                                </div>
                                @error('paymentProof')
                                    <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror

                                @if ($paymentProof)
                                    <button wire:click="uploadPaymentProof"
                                        class="megasena-gradient mt-4 w-full transform rounded-xl py-3 text-base font-bold text-white transition-all hover:scale-105 hover:shadow-xl"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            📤 Enviar Comprovante
                                        </span>
                                        <span wire:loading>
                                            Enviando...
                                        </span>
                                    </button>
                                @endif
                            </div>

                            <div wire:loading class="text-center">
                                <div class="inline-block">
                                    <div
                                        class="h-8 w-8 animate-spin rounded-full border-4 border-green-300 border-t-green-600">
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-gray-600">Enviando seu comprovante...</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Seção de Abstenção -->
                @if ($participant->abstained)
                    <div class="border-t border-gray-200 pt-6">
                        <div class="rounded-xl border-2 border-yellow-200 bg-yellow-50 p-6">
                            <div class="mb-4 flex items-start gap-4">
                                <svg class="h-8 w-8 flex-shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M4.22 4.22a9 9 0 1112.56 12.56M4.22 4.22a9 9 0 0012.56 12.56" />
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-yellow-900">Você se absteve desta votação</h3>
                                    <p class="mt-1 text-sm text-yellow-800">Você pode escolher seus números agora se mudar de ideia</p>
                                </div>
                            </div>

                            @if (!$editingNumbers)
                                <button wire:click="startEditingNumbers"
                                    class="w-full rounded-lg bg-yellow-600 px-4 py-2 font-semibold text-white transition-all hover:bg-yellow-700">
                                    📝 Escolher Números Agora
                                </button>
                            @else
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <p class="mb-3 text-sm font-semibold text-yellow-900">
                                            Selecione {{ $this->numbersToPickProperty }} números ({{ count($selectedNumbers) }}/{{ $this->numbersToPickProperty }}):
                                        </p>
                                        <div class="grid grid-cols-6 gap-2">
                                            @for ($i = $this->minNumberProperty; $i <= $this->maxNumberProperty; $i++)
                                                <button type="button" wire:click="toggleNumberEdit({{ $i }})"
                                                    class="{{ in_array($i, $selectedNumbers) ? 'bg-yellow-600 text-white shadow-lg' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }} flex aspect-square items-center justify-center rounded-lg font-bold transition-all">
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </button>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <button wire:click="cancelEditNumbers"
                                            class="flex-1 rounded-lg bg-gray-300 px-4 py-2 font-semibold text-gray-800 transition-all hover:bg-gray-400">
                                            Cancelar
                                        </button>
                                        <button wire:click="saveNumbers"
                                            class="flex-1 rounded-lg bg-green-600 px-4 py-2 font-semibold text-white transition-all hover:bg-green-700"
                                            wire:loading.attr="disabled"
                                            :disabled="count($selectedNumbers) !== {{ $this->numbersToPickProperty }}">
                                            <span wire:loading.remove>💾 Salvar Números</span>
                                            <span wire:loading>Salvando...</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Números Escolhidos -->
                @if (!$editingNumbers)
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-gray-700">
                        @if ($participant->abstained)
                            Seu status: Abstenção (sem números)
                        @else
                            Seus números votados:
                        @endif
                    </h3>
                    @if (!$participant->abstained && count($participant->sorted_numbers) > 0)
                    <div class="grid grid-cols-3 gap-3 sm:grid-cols-6">
                        @foreach ($participant->sorted_numbers as $number)
                            @php
                                $isPopular = in_array($number, $matchingNumbers);
                            @endphp
                            <div
                                class="{{ $isPopular ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 ring-4 ring-yellow-200' : 'bg-gradient-to-br from-green-500 to-green-600' }} relative flex aspect-square transform items-center justify-center rounded-2xl text-2xl font-bold text-white shadow-lg transition-transform hover:scale-105">
                                {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                @if ($isPopular)
                                    <div class="absolute -right-2 -top-2 rounded-full bg-yellow-500 p-1">
                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if (count($matchingNumbers) > 0)
                        <div class="mt-4 rounded-xl border-2 border-yellow-200 bg-yellow-50 p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-yellow-800">
                                        🎯 Você acertou {{ count($matchingNumbers) }}
                                        {{ count($matchingNumbers) == 1 ? 'número' : 'números' }} dos mais populares!
                                    </p>
                                    <p class="mt-1 text-xs text-yellow-700">
                                        Os números destacados em dourado estão entre os 10 mais escolhidos por todos os
                                        participantes.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
                @endif

                <!-- Números Mais Escolhidos -->
                @if (count($mostChosenNumbers) > 0)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-700">
                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Top 10 - Números mais escolhidos
                        </h3>
                        <div class="grid grid-cols-5 gap-3 sm:grid-cols-10">
                            @foreach ($mostChosenNumbers as $number => $count)
                                @php
                                    $userHasThis = in_array($number, $participant->numbers);
                                @endphp
                                <div class="relative">
                                    <div
                                        class="{{ $userHasThis ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 ring-2 ring-yellow-300' : 'bg-gradient-to-br from-gray-100 to-gray-200' }} {{ $userHasThis ? 'text-white' : 'text-gray-700' }} flex aspect-square items-center justify-center rounded-xl text-lg font-bold shadow">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-gray-800 px-2 py-0.5 text-xs text-white">
                                        {{ $count }}x
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-center text-xs text-gray-500">
                            Quantidade de vezes que cada número foi escolhido por todos os participantes
                        </p>
                    </div>
                @endif

                <!-- Informações Adicionais -->
                <div class="space-y-2 rounded-2xl bg-gray-50 p-6 text-sm text-gray-600">
                    @if ($participant->email)
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $participant->email }}</span>
                        </div>
                    @endif

                    @if ($participant->phone)
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $participant->formatted_phone }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Registrado em: {{ $participant->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($participant->paid && $participant->paid_at)
                        <div class="flex items-center gap-2 text-green-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">Pago em:
                                {{ $participant->paid_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Mensagem Final -->
                <div class="pt-4 text-center">
                    <p
                        class="bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-lg font-semibold text-transparent">
                        🍀 Obrigado por participar da votação! 🍀
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Os números mais votados pelo grupo serão jogados
                    </p>
                </div>
            </div>
        @elseif($searched && !$participant)
            <div class="animate-fadeIn rounded-3xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-800">Código não encontrado</h3>
                <p class="text-gray-600">
                    Verifique se digitou o código corretamente ou entre em contato com o organizador.
                </p>
            </div>
        @endif

        <!-- Botão Voltar -->
        <div class="mt-8 text-center">
            <a href="/"
                class="inline-flex items-center gap-2 font-semibold text-green-600 transition-colors hover:text-green-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para página inicial
            </a>
        </div>
    </div>
</div>
