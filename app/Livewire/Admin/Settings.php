<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Settings extends Component
{
    use Interactions;

    public int $numbersToPickProperty;
    public int $minNumberProperty;
    public int $maxNumberProperty;
    public float $defaultBetAmount;

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        $this->numbersToPickProperty = (int) Setting::get('numbers_to_pick', 6);
        $this->minNumberProperty = (int) Setting::get('min_number', 1);
        $this->maxNumberProperty = (int) Setting::get('max_number', 60);
        $this->defaultBetAmount = (float) Setting::get('default_bet_amount', 5.00);
    }

    public function rules(): array
    {
        return [
            'numbersToPickProperty' => 'required|integer|min:1|max:20',
            'minNumberProperty' => 'required|integer|min:1',
            'maxNumberProperty' => 'required|integer|min:1|max:100',
            'defaultBetAmount' => 'required|numeric|min:0.01',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->minNumberProperty >= $this->maxNumberProperty) {
            $this->toast()
                ->error('O número mínimo deve ser menor que o número máximo!')
                ->send();
            return;
        }

        if ($this->numbersToPickProperty > ($this->maxNumberProperty - $this->minNumberProperty + 1)) {
            $this->toast()
                ->error('A quantidade de números a escolher não pode ser maior que o intervalo disponível!')
                ->send();
            return;
        }

        try {
            // Salvar no banco de dados
            Setting::set('numbers_to_pick', $this->numbersToPickProperty);
            Setting::set('min_number', $this->minNumberProperty);
            Setting::set('max_number', $this->maxNumberProperty);
            Setting::set('default_bet_amount', $this->defaultBetAmount);

            $this->toast()
                ->success('✅ Configurações salvas com sucesso!')
                ->send();

        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao salvar configurações: ' . $e->getMessage())
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.app');
    }
}
