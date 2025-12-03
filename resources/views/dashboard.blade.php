<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="mb-2 text-4xl font-bold text-gray-800 dark:text-gray-200">
                🍀 Painel Administrativo - Mega-Sena
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Gestão completa do sistema de apostas
            </p>
        </div>

        @if (auth()->user()->is_admin)
            <!-- Ações Rápidas -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <x-card class="cursor-pointer transition-shadow hover:shadow-lg"
                    onclick="window.location='{{ route('admin.participants') }}'">
                    <div class="py-6 text-center">
                        <div class="mb-4 text-6xl">💰</div>
                        <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                            Gerenciar Participantes
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Controle de pagamentos e apostas
                        </p>
                    </div>
                </x-card>

                <x-card class="cursor-pointer transition-shadow hover:shadow-lg"
                    onclick="window.location='{{ route('admin.statistics') }}'">
                    <div class="py-6 text-center">
                        <div class="mb-4 text-6xl">📊</div>
                        <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                            Estatísticas
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Análise dos números mais escolhidos
                        </p>
                    </div>
                </x-card>

                <x-card class="cursor-pointer transition-shadow hover:shadow-lg"
                    onclick="window.location='{{ route('users.index') }}'">
                    <div class="py-6 text-center">
                        <div class="mb-4 text-6xl">👥</div>
                        <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">
                            Usuários Admin
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Gerenciar administradores
                        </p>
                    </div>
                </x-card>
            </div>

            <!-- Controle de Votação -->
            <livewire:admin.voting-control />

            <!-- Informações -->
            <x-card title="ℹ️ Como Funciona">
                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">1️⃣</div>
                        <div>
                            <h4 class="mb-1 font-semibold">Participantes apostam publicamente</h4>
                            <p class="text-sm">Qualquer pessoa pode acessar <code
                                    class="rounded bg-gray-100 px-2 py-1 dark:bg-gray-800">{{ url('/') }}</code> e
                                fazer sua aposta.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="text-2xl">2️⃣</div>
                        <div>
                            <h4 class="mb-1 font-semibold">Código de acesso é gerado</h4>
                            <p class="text-sm">Cada participante recebe um código único para consultar sua aposta.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="text-2xl">3️⃣</div>
                        <div>
                            <h4 class="mb-1 font-semibold">Admin gerencia pagamentos</h4>
                            <p class="text-sm">Você marca quem pagou e acompanha o valor total arrecadado.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="text-2xl">4️⃣</div>
                        <div>
                            <h4 class="mb-1 font-semibold">Análise estatística</h4>
                            <p class="text-sm">Veja quais números são mais escolhidos pelos participantes.</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Link Público -->
            <x-card title="🔗 Link Público de Apostas">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <x-input value="{{ url('/') }}" readonly x-data
                            x-on:click="$el.select(); navigator.clipboard.writeText($el.value)" />
                    </div>
                    <x-button color="green" icon="clipboard" x-data
                        x-on:click="navigator.clipboard.writeText('{{ url('/') }}'); alert('Link copiado!')">
                        Copiar Link
                    </x-button>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Compartilhe este link para que as pessoas façam suas apostas.
                </p>
            </x-card>
        @else
            <x-card>
                <x-alert color="amber">
                    Você não tem permissões administrativas. Entre em contato com um administrador.
                </x-alert>
            </x-card>
        @endif
    </div>
</x-app-layout>
