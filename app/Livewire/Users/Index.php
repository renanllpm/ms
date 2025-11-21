<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class Index extends Component
{
    use WithPagination;
    use Interactions;

    public ?int $quantity = 5;

    public ?string $search = null;

    public function mount(): void
    {
        // Verificar se o usuário é admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta página.');
        }
    }

    public array $sort = [
        'column'    => 'created_at',
        'direction' => 'desc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#'],
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'email', 'label' => 'E-mail'],
        ['index' => 'is_admin', 'label' => 'Admin', 'sortable' => false],
        ['index' => 'created_at', 'label' => 'Created'],
        ['index' => 'action', 'sortable' => false],
    ];

    public function toggleAdmin(int $userId): void
    {
        // Apenas admins podem gerenciar outros admins
        if (!Auth::user()->is_admin) {
            $this->toast()
                ->error('Acesso negado! Apenas administradores podem gerenciar permissões.')
                ->send();
            return;
        }

        $user = User::findOrFail($userId);
        
        // Não pode remover o próprio status de admin
        if ($user->id === Auth::id()) {
            $this->toast()
                ->warning('Você não pode alterar seu próprio status de administrador!')
                ->send();
            return;
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'administrador' : 'usuário comum';
        
        $this->toast()
            ->success("Usuário '{$user->name}' agora é {$status}!")
            ->send();
    }

    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return User::query()
            ->whereNotIn('id', [Auth::id()])
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['name', 'email'], 'like', '%'.trim($this->search).'%'))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }
}
