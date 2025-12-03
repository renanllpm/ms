<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class VotingControl extends Component
{
    use Interactions;

    #[Computed]
    public function votingStatus(): string
    {
        return Setting::get('voting_status', 'open');
    }

    #[Computed]
    public function isVotingOpen(): bool
    {
        return $this->votingStatus === 'open';
    }

    public function toggleVotingStatus(): void
    {
        $currentStatus = Setting::get('voting_status', 'open');
        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';

        Setting::set('voting_status', $newStatus);

        $message = $newStatus === 'closed'
            ? '🔒 Votação encerrada com sucesso!'
            : '🔓 Votação reabierta com sucesso!';

        $this->toast()->success($message)->send();
    }

    public function render()
    {
        return view('livewire.admin.voting-control');
    }
}
