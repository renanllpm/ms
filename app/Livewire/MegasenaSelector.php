<?php

namespace App\Livewire;

use App\Models\MegasenaChoice;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class MegasenaSelector extends Component
{
    use Interactions;

    public array $selectedNumbers = [];
    public bool $hasChosen = false;
    public array $savedNumbers = [];

    public function mount(): void
    {
        $choice = auth()->user()->megasenaChoice;
        
        if ($choice) {
            $this->hasChosen = true;
            $this->savedNumbers = $choice->sorted_numbers;
        }
    }

    public function toggleNumber(int $number): void
    {
        if ($this->hasChosen) {
            return;
        }

        $index = array_search($number, $this->selectedNumbers);
        
        if ($index !== false) {
            // Remove o número se já estiver selecionado
            unset($this->selectedNumbers[$index]);
            $this->selectedNumbers = array_values($this->selectedNumbers);
        } else {
            // Adiciona apenas se não ultrapassar 6 números
            if (count($this->selectedNumbers) < 6) {
                $this->selectedNumbers[] = $number;
                sort($this->selectedNumbers);
            } else {
                $this->toast()
                    ->warning('Você já selecionou 6 números!')
                    ->send();
            }
        }
    }

    public function generateRandom(): void
    {
        if ($this->hasChosen) {
            return;
        }

        $this->selectedNumbers = [];
        
        while (count($this->selectedNumbers) < 6) {
            $num = rand(1, 60);
            if (!in_array($num, $this->selectedNumbers)) {
                $this->selectedNumbers[] = $num;
            }
        }
        
        sort($this->selectedNumbers);
        
        $this->toast()
            ->info('Números gerados aleatoriamente!')
            ->send();
    }

    public function clearSelection(): void
    {
        if ($this->hasChosen) {
            return;
        }
        
        $this->selectedNumbers = [];
        
        $this->toast()
            ->info('Seleção limpa!')
            ->send();
    }

    public function saveChoice(): void
    {
        if ($this->hasChosen) {
            $this->toast()
                ->error('Você já fez sua escolha!')
                ->send();
            return;
        }

        $this->validate([
            'selectedNumbers' => 'required|array|size:6',
            'selectedNumbers.*' => 'integer|min:1|max:60|distinct'
        ], [
            'selectedNumbers.required' => 'Você precisa selecionar 6 números!',
            'selectedNumbers.size' => 'Você deve selecionar exatamente 6 números!',
            'selectedNumbers.*.integer' => 'Todos os números devem ser inteiros!',
            'selectedNumbers.*.min' => 'Os números devem ser entre 1 e 60!',
            'selectedNumbers.*.max' => 'Os números devem ser entre 1 e 60!',
            'selectedNumbers.*.distinct' => 'Os números não podem se repetir!',
        ]);

        try {
            MegasenaChoice::create([
                'user_id' => auth()->id(),
                'numbers' => $this->selectedNumbers
            ]);

            $this->hasChosen = true;
            $this->savedNumbers = $this->selectedNumbers;
            
            $this->toast()
                ->success('✅ Números salvos com sucesso! Boa sorte!')
                ->send();
        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao salvar. Você já fez sua escolha!')
                ->send();
        }
    }

    public function isSelected(int $number): bool
    {
        return in_array($number, $this->hasChosen ? $this->savedNumbers : $this->selectedNumbers);
    }

    public function canSave(): bool
    {
        return !$this->hasChosen && count($this->selectedNumbers) === 6;
    }

    public function render()
    {
        return view('livewire.megasena-selector');
    }
}
