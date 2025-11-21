<?php

namespace App\Livewire\Admin;

use App\Models\MegasenaChoice;
use Illuminate\Support\Collection;
use Livewire\Component;

class Statistics extends Component
{
    public array $numberFrequency = [];
    public int $totalChoices = 0;
    public int $totalNumbers = 0;
    public array $topNumbers = [];
    public array $leastNumbers = [];
    public Collection $recentChoices;

    public function mount(): void
    {
        $this->calculateStatistics();
    }

    public function calculateStatistics(): void
    {
        $choices = MegasenaChoice::with('user')->get();
        $this->totalChoices = $choices->count();
        
        // Inicializar array de frequência (1-60)
        $frequency = array_fill(1, 60, 0);
        
        // Contar frequência de cada número
        foreach ($choices as $choice) {
            foreach ($choice->numbers as $number) {
                $frequency[$number]++;
            }
        }
        
        $this->numberFrequency = $frequency;
        $this->totalNumbers = array_sum($frequency);
        
        // Ordenar por frequência (decrescente)
        arsort($frequency);
        
        // Top 10 números mais escolhidos
        $this->topNumbers = array_slice($frequency, 0, 10, true);
        
        // Top 10 números menos escolhidos
        asort($frequency);
        $this->leastNumbers = array_slice($frequency, 0, 10, true);
        
        // Últimas 5 escolhas
        $this->recentChoices = MegasenaChoice::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getPercentage(int $count): float
    {
        if ($this->totalChoices === 0) {
            return 0;
        }
        return round(($count / $this->totalChoices) * 100, 1);
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
