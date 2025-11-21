<div class="min-h-screen">
    @if($showSuccess)
        <!-- ===== TELA DE SUCESSO ===== -->
        <div class="megasena-gradient min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-2xl">
                <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center">
                    <!-- Ícone de Sucesso -->
                    <div class="success-check mb-8">
                        <div class="w-24 h-24 md:w-32 md:h-32 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 md:w-20 md:h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        Aposta Confirmada!
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Sua aposta foi registrada com sucesso
                    </p>

                    <!-- Código de Acesso -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 md:p-8 mb-8">
                        <p class="text-sm text-gray-600 mb-3">Seu código de acesso:</p>
                        <div class="text-4xl md:text-5xl font-bold text-green-700 tracking-widest mb-4 font-mono">
                            {{ $accessCode }}
                        </div>
                        <p class="text-xs md:text-sm text-gray-500">
                            ⚠️ Guarde este código para consultar sua aposta
                        </p>
                    </div>

                    <!-- Números Escolhidos -->
                    <div class="mb-8">
                        <p class="text-sm font-semibold text-gray-700 mb-4">Seus Números da Sorte:</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            @foreach($selectedNumbers as $number)
                                <div class="w-14 h-14 md:w-16 md:h-16 megasena-ball selected rounded-full flex items-center justify-center text-white text-xl md:text-2xl font-bold">
                                    {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informações -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-center">
                        <div>
                            <span class="text-gray-500">Nome:</span>
                            <p class="font-semibold text-gray-800 text-lg">{{ $name }}</p>
                        </div>
                    </div>

                    <button 
                        wire:click="newBet"
                        class="w-full md:w-auto px-8 py-4 megasena-gradient text-white font-semibold rounded-xl hover:shadow-xl transition-all transform hover:scale-105"
                    >
                        Fazer Nova Aposta
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- ===== FORMULÁRIO DE APOSTA ===== -->
        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="megasena-gradient text-white py-8 md:py-12 px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-3 tracking-tight">
                        🍀 Mega-Sena
                    </h1>
                    <p class="text-lg md:text-xl text-green-100">
                        Faça sua aposta e concorra ao prêmio!
                    </p>
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="max-w-4xl mx-auto px-4 -mt-8">
                <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-10">
                    
                    <!-- Números Selecionados (Preview Top) -->
                    @if(count($selectedNumbers) > 0)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-gray-700">
                                    Números Selecionados:
                                </span>
                                <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold">
                                    {{ count($selectedNumbers) }}/{{ $this->numbersToPickProperty }}
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap justify-center md:justify-start">
                                @foreach($selectedNumbers as $number)
                                    <div class="w-12 h-12 megasena-ball selected rounded-full flex items-center justify-center text-white text-lg font-bold">
                                        {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl text-center">
                            <p class="text-green-700 font-medium">
                                👆 Escolha {{ $this->numbersToPickProperty }} números de {{ $this->minNumberProperty }} a {{ $this->maxNumberProperty }}
                            </p>
                            <p class="text-green-600 font-bold text-xl mt-3">
                                💰 Valor da Aposta: R$ {{ number_format(\App\Models\Setting::get('default_bet_amount', 5.00), 2, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    <!-- Grid de Números -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Escolha seus números</h3>
                            <div class="flex gap-2">
                                <button
                                    wire:click="generateRandom"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    🎲 Surpresinha
                                </button>
                                <button
                                    wire:click="clearSelection"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    ✖️ Limpar
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-6 gap-3">
                            @for($i = $this->minNumberProperty; $i <= $this->maxNumberProperty; $i++)
                                <button
                                    type="button"
                                    wire:click="toggleNumber({{ $i }})"
                                    class="megasena-ball {{ $this->isSelected($i) ? 'selected' : '' }} w-full aspect-square rounded-full flex items-center justify-center text-white font-bold text-base md:text-lg shadow-md"
                                    wire:loading.attr="disabled"
                                >
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </button>
                            @endfor
                        </div>
                    </div>

                    <hr class="my-8 border-gray-200">

                    <!-- Formulário de Dados -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Seus Dados</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                            <input 
                                type="text"
                                wire:model="name"
                                placeholder="Digite seu nome completo"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            >
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if(config('megasena.allow_optional_proof', true))
                    <hr class="my-8 border-gray-200">
                    
                    <!-- Upload de Comprovante -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Comprovante de Pagamento (Opcional)</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-500 transition-colors">
                            <input 
                                type="file" 
                                wire:model="paymentProof"
                                accept="image/*,.pdf"
                                class="hidden"
                                id="fileUpload"
                            >
                            <label for="fileUpload" class="cursor-pointer">
                                <div class="text-4xl mb-3">📎</div>
                                <p class="text-gray-600 mb-2">Clique para enviar o comprovante</p>
                                <p class="text-xs text-gray-500">JPG, PNG ou PDF (Máx: 5MB)</p>
                            </label>
                            @if($paymentProof)
                                <p class="text-green-600 text-sm mt-3 font-medium">✓ Arquivo selecionado</p>
                            @endif
                        </div>
                        @error('paymentProof') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Botão de Confirmar -->
                    <button
                        wire:click="submitBet"
                        {{ !$this->canSubmit() ? 'disabled' : '' }}
                        class="w-full py-4 megasena-gradient text-white font-bold text-lg rounded-xl hover:shadow-xl transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            ✅ Confirmar Aposta
                        </span>
                        <span wire:loading>
                            Processando...
                        </span>
                    </button>

                    @if(count($selectedNumbers) < $this->numbersToPickProperty && count($selectedNumbers) > 0)
                        <p class="text-center text-sm text-gray-500 mt-4">
                            Selecione mais {{ $this->numbersToPickProperty - count($selectedNumbers) }} número(s)
                        </p>
                    @endif
                </div>

                <!-- Footer -->
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-2">💚 Boa sorte!</p>
                    <p class="text-xs text-gray-400">Sistema Mega-Sena © {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 shadow-2xl text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-green-600 mb-4"></div>
            <p class="text-lg font-semibold text-gray-700">Processando...</p>
        </div>
    </div>
</div>
