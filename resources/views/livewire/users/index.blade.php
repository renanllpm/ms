<div>
    <x-card>
        @if (auth()->user()->is_admin)
            <x-alert color="blue" icon="shield-check">
                @lang('Você é um administrador. Você pode gerenciar permissões de outros usuários.')
            </x-alert>
        @else
            <x-alert color="amber" icon="light-bulb">
                @lang('Remember to take a look at the source code to understand how the components in this area were built and are being used.')
            </x-alert>
        @endif

        <div class="mb-2 mt-4">
            <livewire:users.create @created="$refresh" />
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate simple-pagination filter loading :quantity="[2, 5, 15, 25]">
            @interact('column_is_admin', $row)
                <div class="flex items-center gap-2">
                    @if ($row->is_admin)
                        <x-badge color="green" text="Admin" icon="shield-check" sm />
                    @else
                        <x-badge color="gray" text="Usuário" icon="user" sm />
                    @endif

                    @if (auth()->user()->is_admin)
                        <x-button.circle :color="$row->is_admin ? 'red' : 'green'" :icon="$row->is_admin ? 'shield-exclamation' : 'shield-check'" wire:click="toggleAdmin({{ $row->id }})"
                            x-tooltip="'{{ $row->is_admin ? 'Remover Admin' : 'Tornar Admin' }}'" xs />
                    @endif
                </div>
            @endinteract

            @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
                <div class="flex gap-1">
                    <x-button.circle icon="pencil"
                        wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})" />
                    <livewire:users.delete :user="$row" :key="uniqid('', true)" @deleted="$refresh" />
                </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:users.update @updated="$refresh" />
</div>
