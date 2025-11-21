<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                ⚙️ Configurações do Sistema
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Configure os parâmetros da Mega-Sena
            </p>
        </div>
    </div>

    <!-- Card de Configurações -->
    <x-card title="Configurações da Mega-Sena" class="max-w-2xl">
        <div class="space-y-6">
            <!-- Quantidade de Números -->
            <div>
                <x-input 
                    label="Quantidade de Números a Escolher" 
                    wire:model="numbersToPickProperty"
                    type="number"
                    min="1"
                    max="20"
                    hint="Quantos números o participante deve escolher (ex: 6 para Mega-Sena tradicional)"
                />
            </div>

            <!-- Range de Números -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input 
                        label="Número Mínimo" 
                        wire:model="minNumberProperty"
                        type="number"
                        min="1"
                        hint="Número inicial (ex: 1)"
                    />
                </div>

                <div>
                    <x-input 
                        label="Número Máximo" 
                        wire:model="maxNumberProperty"
                        type="number"
                        min="1"
                        max="100"
                        hint="Número final (ex: 60)"
                    />
                </div>
            </div>

            <!-- Valor da Aposta -->
            <div>
                <x-input 
                    label="Valor da Aposta (R$)" 
                    wire:model="defaultBetAmount"
                    type="number"
                    step="0.01"
                    min="0.01"
                    prefix="R$"
                    hint="Valor que será cobrado por cada aposta"
                />
            </div>

            <!-- Preview -->
            <x-alert color="blue" class="mt-6">
                <div class="space-y-2">
                    <p class="font-semibold">📋 Preview das Configurações:</p>
                    <p class="text-sm">
                        • Os participantes escolherão <strong>{{ $numbersToPickProperty }} números</strong> 
                        de <strong>{{ $minNumberProperty }}</strong> até <strong>{{ $maxNumberProperty }}</strong>
                    </p>
                    <p class="text-sm">
                        • Cada aposta custará <strong>R$ {{ number_format($defaultBetAmount, 2, ',', '.') }}</strong>
                    </p>
                    <p class="text-sm">
                        • Total de números disponíveis: <strong>{{ $maxNumberProperty - $minNumberProperty + 1 }}</strong>
                    </p>
                </div>
            </x-alert>

            <!-- Botões -->
            <div class="flex justify-between gap-4 pt-4">
                <x-button 
                    wire:click="loadSettings" 
                    color="slate"
                    icon="arrow-path"
                >
                    Resetar
                </x-button>

                <x-button 
                    wire:click="save" 
                    color="green"
                    icon="check-circle"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Salvar Configurações</span>
                    <span wire:loading>Salvando...</span>
                </x-button>
            </div>
        </div>
    </x-card>

            <!-- Informações Adicionais -->
    <x-card title="ℹ️ Informações Importantes" class="max-w-2xl">
        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
            <p>• As configurações são salvas no <strong>banco de dados</strong></p>
            <p>• Alterar essas configurações afeta todas as novas apostas</p>
            <p>• Apostas já realizadas não serão modificadas</p>
            <p>• As configurações são cacheadas por 1 hora para melhor performance</p>
        </div>
    </x-card>
</div>