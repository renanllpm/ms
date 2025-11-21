<?php

namespace App\Livewire;

use App\Models\MegasenaChoice;
use Illuminate\Support\Collection;
use Livewire\Component;

class ParticipantsList extends Component
{
    public Collection $participants;
    public int $totalParticipants = 0;

    public function mount(): void
    {
        $this->loadParticipants();
    }

    public function loadParticipants(): void
    {
        $this->participants = MegasenaChoice::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $this->totalParticipants = $this->participants->count();
    }

    public function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    public function render()
    {
        return view('livewire.participants-list');
    }
}
