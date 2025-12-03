<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Status da Votação
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Controle se a votação está aberta ou encerrada
            </p>
        </div>

        <div class="text-right">
            @if ($this->isVotingOpen)
                <div class="flex items-center gap-2">
                    <span class="relative inline-flex h-3 w-3 animate-pulse rounded-full bg-green-500"></span>
                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                        Votação Aberta
                    </span>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                        Votação Encerrada
                    </span>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        @if ($this->isVotingOpen)
            <button wire:click="toggleVotingStatus"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition-colors hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                🔒 Encerrar Votação
            </button>
        @else
            <button wire:click="toggleVotingStatus"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 font-semibold text-white transition-colors hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                🔓 Reabrir Votação
            </button>
        @endif
    </div>

    @if (!$this->isVotingOpen)
        <div class="mt-4 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900/20">
            <p class="text-sm text-red-700 dark:text-red-200">
                ⚠️ <strong>Votação encerrada:</strong> Usuários não podem mais votar. Eles podem apenas consultar os
                dados da votação.
            </p>
        </div>
    @endif
</div>
