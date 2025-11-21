<div>
    <x-modal :title="'Atualizar Usuário: #' . $user?->id" wire>
        <form id="user-update-{{ $user?->id }}" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="Nome *" wire:model="user.name" required />
            </div>

            <div>
                <x-input label="Email *" wire:model="user.email" required />
            </div>

            <div>
                <x-password label="Senha" hint="A senha só será atualizada se você preencher este campo"
                    wire:model="password" rules generator
                    x-on:generate="$wire.set('password_confirmation', $event.detail.password)" />
            </div>

            <div>
                <x-password label="Confirmar Senha" wire:model="password_confirmation" rules />
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="user-update-{{ $user?->id }}" loading="save">
                Salvar
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
