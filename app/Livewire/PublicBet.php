<?php

namespace App\Livewire;

use App\Models\Participant;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class PublicBet extends Component
{
    use Interactions;
    use WithFileUploads;

    public string $name = '';
    public string $phone = '';
    public array $selectedNumbers = [];
    public $paymentProof = null;

    public bool $showSuccess = false;
    public string $accessCode = '';

    public function mount(): void
    {
        // Não precisa mais carregar o amount pois é definido pelo admin
    }

    #[Computed]
    public function numbersToPickProperty(): int
    {
        return (int) \App\Models\Setting::get('numbers_to_pick', 6);
    }

    #[Computed]
    public function minNumberProperty(): int
    {
        return (int) \App\Models\Setting::get('min_number', 1);
    }

    #[Computed]
    public function maxNumberProperty(): int
    {
        return (int) \App\Models\Setting::get('max_number', 60);
    }

    public function rules(): array
    {
        $numbersToPick = $this->numbersToPickProperty;

        return [
            'name' => 'required|string|min:3|max:255',
            'phone' => 'nullable|string|min:10|max:20',
            'selectedNumbers' => "required|array|size:{$numbersToPick}",
            'selectedNumbers.*' => 'integer|min:' . $this->minNumberProperty . '|max:' . $this->maxNumberProperty . '|distinct',
            'paymentProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:' . config('megasena.max_proof_size', 5120),
        ];
    }

    public function toggleNumber(int $number): void
    {
        if ($this->showSuccess) {
            return;
        }

        $index = array_search($number, $this->selectedNumbers);

        if ($index !== false) {
            unset($this->selectedNumbers[$index]);
            $this->selectedNumbers = array_values($this->selectedNumbers);
        } else {
            if (count($this->selectedNumbers) < $this->numbersToPickProperty) {
                $this->selectedNumbers[] = $number;
                sort($this->selectedNumbers);
            } else {
                $this->toast()
                    ->warning("Você já selecionou {$this->numbersToPickProperty} números!")
                    ->send();
            }
        }
    }

    public function generateRandom(): void
    {
        if ($this->showSuccess) {
            return;
        }

        $this->selectedNumbers = [];
        $min = $this->minNumberProperty;
        $max = $this->maxNumberProperty;
        $count = $this->numbersToPickProperty;

        while (count($this->selectedNumbers) < $count) {
            $num = rand($min, $max);
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
        if ($this->showSuccess) {
            return;
        }

        $this->selectedNumbers = [];

        $this->toast()
            ->info('Seleção limpa!')
            ->send();
    }

    public function submitBet(): void
    {
        $this->validate();

        try {
            $accessCode = Participant::generateAccessCode();

            // Ordenar os números antes de salvar
            $sortedNumbers = $this->selectedNumbers;
            sort($sortedNumbers);

            $participantData = [
                'name' => $this->name,
                'email' => null,
                'phone' => $this->phone ?: null,
                'access_code' => $accessCode,
                'numbers' => $sortedNumbers,
                'amount' => (float) \App\Models\Setting::get('default_bet_amount', 5.00), // Contribuição por pessoa
                'paid' => false,
            ];

            // Upload do comprovante se enviado
            if ($this->paymentProof) {
                $path = $this->paymentProof->store('payment-proofs', 'public');
                $participantData['payment_proof'] = $path;
            }

            Participant::create($participantData);

            $this->accessCode = $accessCode;
            $this->showSuccess = true;

            $this->toast()
                ->success('✅ Voto registrado com sucesso!')
                ->send();

        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao registrar voto. Tente novamente.')
                ->send();
        }
    }

    public function newBet(): void
    {
        $this->reset();
    }

    public function isSelected(int $number): bool
    {
        return in_array($number, $this->selectedNumbers);
    }

    public function canSubmit(): bool
    {
        return !$this->showSuccess &&
            count($this->selectedNumbers) === $this->numbersToPickProperty &&
            !empty($this->name);
    }

    public function render()
    {
        return view('livewire.public-bet')->layout('layouts.public');
    }
}
