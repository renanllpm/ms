<div @updated="$dispatch('name-updated', { name: $event.detail.name })" class="min-h-screen bg-gray-50 py-8">
    <style>
        /* Animação para feedback visual */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
    <div class="mx-auto max-w-2xl px-4">
        <!-- Header com gradiente -->
        <div class="mb-8 text-center">
            <div
                class="mb-4 inline-flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-3xl font-bold text-white shadow-lg">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Meu Perfil</h1>
            <p class="mt-2 text-gray-600">Gerencie suas informações pessoais</p>
        </div>

        <!-- Card do Formulário -->
        <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
            <form wire:submit="save" class="space-y-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nome Completo *
                    </label>
                    <input type="text" id="name" wire:model="user.name" required
                        class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                        placeholder="Digite seu nome completo">
                    @error('user.name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (Desabilitado) -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                        class="w-full cursor-not-allowed rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-gray-500">
                    <p class="mt-1 text-xs text-gray-500">O email não pode ser alterado</p>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Alterar Senha</h3>
                    <p class="mb-4 text-sm text-gray-600">
                        Deixe em branco se não quiser alterar a senha
                    </p>
                </div>

                <!-- Nova Senha -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nova Senha
                    </label>
                    <input type="password" id="password" wire:model="password"
                        class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                        placeholder="••••••••" autocomplete="new-password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Mínimo de 8 caracteres
                    </p>
                </div>

                <!-- Confirmar Senha -->
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                        Confirmar Nova Senha
                    </label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                        class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition-all focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                        placeholder="••••••••" autocomplete="new-password">
                </div>

                <!-- Botão de Salvar -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 font-semibold text-white shadow-lg transition-all hover:from-green-600 hover:to-emerald-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-green-200">
                        <span wire:loading.remove>Salvar Alterações</span>
                        <span wire:loading>
                            <svg class="inline h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Salvando...
                        </span>
                    </button>
                </div>
            </form>
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
</div>
