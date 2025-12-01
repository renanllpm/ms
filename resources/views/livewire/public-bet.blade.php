<div class="min-h-screen">
    @if ($showSuccess)
        <!-- ===== TELA DE SUCESSO ===== -->
        <div class="megasena-gradient flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-2xl">
                <div class="rounded-3xl bg-white p-8 text-center shadow-2xl md:p-12">
                    <!-- Ícone de Sucesso -->
                    <div class="success-check mb-8">
                        <div
                            class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-green-100 md:h-32 md:w-32">
                            <svg class="h-16 w-16 text-green-600 md:h-20 md:w-20" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <h2 class="mb-4 text-3xl font-bold text-gray-800 md:text-4xl">
                        @if ($abstained)
                            Abstenção Registrada!
                        @else
                            Voto Registrado!
                        @endif
                    </h2>
                    <p class="mb-8 text-gray-600">
                        @if ($abstained)
                            Sua abstenção foi registrada com sucesso. Os números votados pelos demais integrantes serão
                            jogados!
                        @else
                            Seu voto foi registrado com sucesso. Os números mais votados serão jogados pelo grupo!
                        @endif
                    </p>

                    <!-- Código de Acesso -->
                    <div class="mb-8 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 p-6 md:p-8">
                        <p class="mb-3 text-sm text-gray-600">Seu código de consulta:</p>
                        <div class="mb-4 font-mono text-4xl font-bold tracking-widest text-green-700 md:text-5xl">
                            {{ $accessCode }}
                        </div>
                        <p class="text-xs text-gray-500 md:text-sm">
                            ⚠️ Guarde este código para consultar seu voto e ver os números mais votados
                        </p>
                    </div>

                    <!-- Números Escolhidos -->
                    @if (!$abstained && count($selectedNumbers) > 0)
                        <div class="mb-8">
                            <p class="mb-4 text-sm font-semibold text-gray-700">Seus Números Votados:</p>
                            <div class="flex flex-wrap justify-center gap-3">
                                @foreach ($selectedNumbers as $number)
                                    <div
                                        class="megasena-ball selected flex h-14 w-14 items-center justify-center rounded-full text-xl font-bold text-white md:h-16 md:w-16 md:text-2xl">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Informações -->
                    <div class="mb-8 rounded-2xl bg-gray-50 p-6 text-center">
                        <div>
                            <span class="text-gray-500">Nome:</span>
                            <p class="text-lg font-semibold text-gray-800">{{ $name }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <button wire:click="newBet"
                            class="megasena-gradient transform rounded-xl px-8 py-4 font-semibold text-white transition-all hover:scale-105 hover:shadow-xl">
                            Fazer Nova Aposta
                        </button>

                        <a href="{{ route('check.bet') }}"
                            class="inline-flex transform items-center justify-center gap-2 rounded-xl border-2 border-green-200 bg-green-50 px-8 py-4 font-semibold text-green-700 transition-all hover:scale-105 hover:bg-green-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Consultar Aposta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- ===== FORMULÁRIO DE APOSTA ===== -->
        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="megasena-gradient px-4 py-8 text-white md:py-12">
                <div class="mx-auto max-w-4xl">
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex-1 text-center">
                            <h1 class="mb-3 text-4xl font-bold tracking-tight md:text-6xl">
                                🍀 Votação Mega-Sena
                            </h1>
                            <p class="text-lg text-green-100 md:text-xl">
                                Vote nos números que o grupo deve jogar!
                            </p>
                        </div>
                        <a href="{{ route('check.bet') }}"
                            class="hidden items-center gap-2 rounded-xl border border-white/30 bg-white/20 px-4 py-2 font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/30 sm:flex">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Consultar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="mx-auto -mt-8 max-w-7xl px-4">
                <div class="rounded-3xl bg-white p-6 shadow-2xl md:p-10">
                    <div class="mb-6 space-y-3">
                        <div class="rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                            <p class="text-center text-sm font-semibold text-blue-800">
                                ℹ️ Este é um sistema de votação colaborativa. Vote nos números que você acha que o grupo
                                deve jogar na Mega-Sena!
                            </p>
                        </div>
                        <div class="rounded-xl border-2 border-green-200 bg-green-50 p-4">
                            <p class="text-center text-sm font-semibold text-green-800">
                                ✓ Você pode votar sem enviar comprovante! O envio é opcional e pode ser feito depois.
                            </p>
                        </div>
                    </div>
                    <!-- Números Selecionados (Preview Top) -->
                    @if (count($selectedNumbers) > 0)
                        <div class="mb-8">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">
                                    Seus Votos:
                                </span>
                                <span class="rounded-full bg-green-100 px-4 py-1 text-sm font-bold text-green-700">
                                    {{ count($selectedNumbers) }}/{{ $this->numbersToPickProperty }}
                                </span>
                            </div>
                            <div class="flex flex-wrap justify-center gap-2 md:justify-start">
                                @foreach ($selectedNumbers as $number)
                                    <div
                                        class="megasena-ball selected flex h-8 w-8 items-center justify-center rounded-full text-lg font-bold text-white">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif (!$abstained)
                        <div class="mb-8 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 p-6 text-center">
                            <p class="font-medium text-green-700">
                                👆 Escolha {{ $this->numbersToPickProperty }} números de {{ $this->minNumberProperty }}
                                a {{ $this->maxNumberProperty }}
                            </p>
                            <p class="mt-3 text-xl font-bold text-green-600">
                                💰 Valor da Aposta: R$
                                {{ number_format(\App\Models\Setting::get('default_bet_amount', 5.0), 2, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    <!-- Grid de Números -->
                    @if (!$abstained)
                        <div class="mb-8">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800">Escolha seus números</h3>
                                <div class="flex gap-2">
                                    <button wire:click="generateRandom"
                                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-purple-700"
                                        wire:loading.attr="disabled">
                                        🎲 Surpresinha
                                    </button>
                                    <button wire:click="clearSelection"
                                        class="rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-700"
                                        wire:loading.attr="disabled">
                                        ✖️ Limpar
                                    </button>
                                </div>
                            </div>

                            <div class="grid-cols-15 grid gap-4">
                                @for ($i = $this->minNumberProperty; $i <= $this->maxNumberProperty; $i++)
                                    <button type="button" wire:click="toggleNumber({{ $i }})"
                                        class="megasena-ball {{ $this->isSelected($i) ? 'selected' : '' }} flex aspect-square w-full items-center justify-center rounded-full text-sm font-bold text-white shadow-md md:text-lg"
                                        wire:loading.attr="disabled">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </button>
                                @endfor
                            </div>
                        </div>
                    @else
                        <div
                            class="mb-8 rounded-2xl border-2 border-yellow-200 bg-gradient-to-r from-yellow-50 to-orange-50 p-6 text-center">
                            <svg class="mx-auto mb-3 h-12 w-12 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4v2m0 4v2M4.22 4.22a9 9 0 1112.56 12.56M4.22 4.22a9 9 0 0012.56 12.56" />
                            </svg>
                            <p class="text-lg font-bold text-yellow-900">Você escolheu se abster desta votação</p>
                            <p class="mt-2 text-sm text-yellow-800">Nenhum número será selecionado para sua participação
                            </p>
                        </div>
                    @endif

                    <hr class="my-8 border-gray-200">

                    <!-- Formulário de Dados -->
                    <div class="mb-8">
                        <h3 class="mb-6 text-lg font-bold text-gray-800">Seus Dados</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Nome Completo *</label>
                                <input type="text" wire:model.live="name" placeholder="Digite seu nome completo"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                                @error('name')
                                    <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                                <input type="tel" wire:model.live="phone"
                                    placeholder="(11) 99999-9999 (opcional)"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-green-500">
                                <p class="mt-1 text-xs text-gray-500">Apenas se você quiser receber via WhatsApp
                                </p>
                                @error('phone')
                                    <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Checkbox de Abstenção -->
                            <div class="rounded-lg border-2 border-yellow-200 bg-yellow-50 p-4">
                                <label class="flex cursor-pointer items-center gap-3">
                                    <input type="checkbox" wire:model.live="abstained"
                                        class="h-5 w-5 rounded border-gray-300 text-yellow-600">
                                    <div>
                                        <p class="font-semibold text-yellow-900">Desejo me abster desta votação</p>
                                        <p class="text-xs text-yellow-800">Se marcar esta opção, você não precisa
                                            escolher números</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    @if (config('megasena.allow_optional_proof', true))
                        <hr class="my-8 border-gray-200">

                        <!-- Upload de Comprovante -->
                        <div class="mb-8">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800">Comprovante de Contribuição</h3>
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                                    Opcional
                                </span>
                            </div>
                            <div class="mb-4 rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                                <p class="text-sm text-blue-800">
                                    ℹ️ Você pode enviar o comprovante agora ou depois. O envio não é obrigatório para
                                    votar.
                                </p>
                            </div>
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
                        </div>
                    @endif

                    <!-- Botão de Confirmar -->
                    <button wire:click="submitBet" {{ !$this->canSubmit() ? 'disabled' : '' }}
                        class="megasena-gradient w-full transform rounded-xl py-4 text-lg font-bold text-white transition-all hover:scale-105 hover:shadow-xl disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            ✅ Registrar Voto
                        </span>
                        <span wire:loading>
                            Processando...
                        </span>
                    </button>

                    @if (!$abstained && count($selectedNumbers) < $this->numbersToPickProperty && count($selectedNumbers) > 0)
                        <p class="mt-4 text-center text-sm text-gray-500">
                            Selecione mais {{ $this->numbersToPickProperty - count($selectedNumbers) }} número(s)
                        </p>
                    @endif

                    @if (!$abstained && count($selectedNumbers) === $this->numbersToPickProperty)
                        <p class="mt-4 text-center text-sm font-medium text-green-600">
                            ✓ Você pode votar agora! O comprovante é opcional.
                        </p>
                    @endif

                    @if ($abstained)
                        <p class="mt-4 text-center text-sm font-medium text-yellow-600">
                            ✓ Você pode registrar sua abstenção agora!
                        </p>
                    @endif
                </div>

                <!-- Footer -->
                <div class="py-8 text-center">
                    <p class="mb-2 text-gray-600">💚 Boa sorte!</p>
                    <p class="text-xs text-gray-400">Sistema Mega-Sena © {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    {{-- <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
        <div class="rounded-2xl bg-white p-8 text-center shadow-2xl">
            <div class="mb-4 inline-block h-16 w-16 animate-spin rounded-full border-b-4 border-t-4 border-green-600">
            </div>
            <p class="text-lg font-semibold text-gray-700">Processando...</p>
        </div>
    </div> --}}
</div>
