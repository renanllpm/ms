<div x-data="{ redirectUrl: null }" @redirect.window="window.open($event.detail.url, '_blank')">
    <!-- Componente para Criar Nova Votação -->
    <livewire:admin.create-participant wire:key="create-participant-{{ now()->timestamp }}"
        @participantCreated="$wire.$refresh()" />

    <!-- Estatísticas Financeiras -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <div class="mb-1 text-sm font-semibold opacity-90">Total de Participantes</div>
            <div class="text-4xl font-bold">{{ $stats['total'] }}</div>
            <div class="mt-2 text-xs opacity-75">
                {{ $stats['paid'] }} pagos / {{ $stats['unpaid'] }} pendentes
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-green-500 to-green-600 text-white">
            <div class="mb-1 text-sm font-semibold opacity-90">Total Arrecadado</div>
            <div class="text-4xl font-bold">R$ {{ number_format($stats['total_amount'], 2, ',', '.') }}</div>
            <div class="mt-2 text-xs opacity-75">
                Pago: R$ {{ number_format($stats['paid_amount'], 2, ',', '.') }}
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-orange-500 to-orange-600 text-white">
            <div class="mb-1 text-sm font-semibold opacity-90">Pendente</div>
            <div class="text-4xl font-bold">R$ {{ number_format($stats['unpaid_amount'], 2, ',', '.') }}</div>
            <div class="mt-2 text-xs opacity-75">
                {{ $stats['unpaid'] }} participante(s)
            </div>
        </x-card>
    </div>

    <!-- Tabela de Participantes -->
    <x-card title="💰 Gerenciar Participantes">
        <x-slot name="action">
            <div class="flex items-center gap-3">
                <x-select.native wire:model.live="filterStatus" :options="[
                    ['label' => 'Todos', 'value' => 'all'],
                    ['label' => 'Pagos', 'value' => 'paid'],
                    ['label' => 'Pendentes', 'value' => 'unpaid'],
                ]" select="label:label|value:value" />
                <x-input wire:model.live="search" placeholder="Buscar por nome, telefone ou código..."
                    icon="magnifying-glass" />
            </div>
        </x-slot>

        @if ($participants->count() === 0)
            <div class="py-12 text-center">
                <div class="mb-4 text-6xl">📊</div>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Nenhum participante encontrado.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Código</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Nome</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Telefone</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Números</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Contribuição</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Comprovante</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                        @foreach ($participants as $participant)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <code class="rounded bg-gray-100 px-2 py-1 font-mono text-xs dark:bg-gray-800">
                                        {{ $participant->access_code }}
                                    </code>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $participant->name }}
                                    </div>
                                    @if ($participant->email)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $participant->email }}
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $participant->formatted_phone }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($participant->abstained)
                                        <div class="rounded-lg bg-yellow-100 px-3 py-2 text-center dark:bg-yellow-900">
                                            <span class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">
                                                ✖️ Abstenção
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($participant->sorted_numbers as $number)
                                                <span
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-xs font-bold text-white">
                                                    {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        R$ {{ number_format($participant->amount, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if ($participant->paid)
                                        <x-badge color="green" text="PAGO" icon="check-circle" sm />
                                        @if ($participant->paid_at)
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $participant->paid_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @else
                                        <x-badge color="red" text="PENDENTE" icon="clock" sm />
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    @if ($participant->payment_proof)
                                        <a href="{{ Storage::url($participant->payment_proof) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                            <x-icon name="document-text" class="h-6 w-6" />
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button.circle color="green" icon="share"
                                            wire:click="shareViaWhatsApp({{ $participant->id }})"
                                            x-tooltip="'Compartilhar via WhatsApp'" xs />
                                        <x-button.circle :color="$participant->paid ? 'red' : 'green'" :icon="$participant->paid ? 'x-circle' : 'check-circle'"
                                            wire:click="togglePaid({{ $participant->id }})"
                                            x-tooltip="'{{ $participant->paid ? 'Marcar como Não Pago' : 'Marcar como Pago' }}'"
                                            xs />
                                        <x-button.circle color="red" icon="trash"
                                            wire:click="deleteParticipant({{ $participant->id }})"
                                            wire:confirm="Tem certeza que deseja remover este participante?"
                                            x-tooltip="'Excluir'" xs />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-4">
                {{ $participants->links() }}
            </div>
        @endif
    </x-card>
</div>
