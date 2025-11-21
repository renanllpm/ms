<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Livewire\Component;

class Statistics extends Component
{
    public array $numberFrequency = [];
    public int $totalVotes = 0;
    public int $totalNumbers = 0;
    public array $topNumbers = [];
    public array $leastNumbers = [];
    public Collection $recentVotes;

    public function mount(): void
    {
        $this->calculateStatistics();
    }

    public function calculateStatistics(): void
    {
        $participants = Participant::all();
        $this->totalVotes = $participants->count();
        
        // Inicializar array de frequência (1-60)
        $frequency = array_fill(1, 60, 0);
        
        // Contar frequência de cada número
        foreach ($participants as $participant) {
            foreach ($participant->numbers as $number) {
                if ($number >= 1 && $number <= 60) {
                    $frequency[$number]++;
                }
            }
        }
        
        $this->numberFrequency = $frequency;
        $this->totalNumbers = array_sum($frequency);
        
        // Ordenar por frequência (decrescente)
        arsort($frequency);
        
        // Top 10 números mais escolhidos
        $this->topNumbers = array_slice($frequency, 0, 10, true);
        
        // Top 10 números menos escolhidos (que tenham pelo menos 1 voto)
        $frequencyWithVotes = array_filter($frequency, fn($count) => $count > 0);
        asort($frequencyWithVotes);
        $this->leastNumbers = array_slice($frequencyWithVotes, 0, 10, true);
        
        // Últimos 5 votos
        $this->recentVotes = Participant::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getPercentage(int $count): float
    {
        if ($this->totalVotes === 0) {
            return 0;
        }
        return round(($count / $this->totalVotes) * 100, 1);
    }

    public function getNumberColor(int $frequency): string
    {
        $percentage = $this->getPercentage($frequency);
        
        if ($percentage >= 50) return 'red';
        if ($percentage >= 30) return 'orange';
        if ($percentage >= 15) return 'yellow';
        if ($percentage >= 5) return 'green';
        return 'gray';
    }

    public function refresh(): void
    {
        $this->calculateStatistics();
        $this->dispatch('statistics-refreshed');
    }

    public function render()
    {
        return view('livewire.admin.statistics');
    }
}
