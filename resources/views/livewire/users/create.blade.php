<div>
    <x-button text="Criar Novo Usuário" wire:click="$toggle('modal')" sm />

    <x-modal title="Criar Novo Usuário" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)">
        <form id="user-create" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="Nome *" x-ref="name" wire:model="user.name" required />
            </div>

            <div>
                <x-input label="Email *" wire:model="user.email" required />
            </div>

            <div>
                <x-password label="Senha *" wire:model="password" rules generator
                    x-on:generate="$wire.set('password_confirmation', $event.detail.password)" required />
            </div>

            <div>
                <x-password label="Confirmar Senha *" wire:model="password_confirmation" rules required />
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="user-create">
                Salvar
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
