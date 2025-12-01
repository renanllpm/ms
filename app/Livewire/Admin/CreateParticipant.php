<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class CreateParticipant extends Component
{
    use Interactions;

    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public array $selectedNumbers = [];
    public string $amount = '';
    public bool $paid = false;
    public bool $modal = false;

    #[Computed]
    public function numbersToPickProperty(): int
    {
        return (int) Setting::get('numbers_to_pick', 6);
    }

    #[Computed]
    public function minNumberProperty(): int
    {
        return (int) Setting::get('min_number', 1);
    }

    #[Computed]
    public function maxNumberProperty(): int
    {
        return (int) Setting::get('max_number', 60);
    }

    #[Computed]
    public function defaultAmountProperty(): string
    {
        return (string) Setting::get('default_bet_amount', 5.00);
    }

    public function mount(): void
    {
        $this->amount = $this->defaultAmountProperty;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['required', 'string', 'min:10'],
            'email' => ['nullable', 'email'],
            'selectedNumbers' => ['required', 'array', 'size:' . $this->numbersToPickProperty],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'phone.required' => 'Telefone é obrigatório',
            'selectedNumbers.size' => 'Você deve selecionar exatamente ' . $this->numbersToPickProperty . ' números',
            'amount.required' => 'Valor da aposta é obrigatório',
        ];
    }

    public function toggleNumber(int $number): void
    {
        if (in_array($number, $this->selectedNumbers)) {
            $this->selectedNumbers = array_filter(
                $this->selectedNumbers,
                fn($n) => $n !== $number
            );
        } else {
            if (count($this->selectedNumbers) < $this->numbersToPickProperty) {
                $this->selectedNumbers[] = $number;
                sort($this->selectedNumbers);
            }
        }
    }

    public function generateRandom(): void
    {
        $this->selectedNumbers = array_values(
            array_rand(
                array_flip(range($this->minNumberProperty, $this->maxNumberProperty)),
                $this->numbersToPickProperty
            )
        );
        sort($this->selectedNumbers);
    }

    public function clearSelection(): void
    {
        $this->selectedNumbers = [];
    }

    public function createParticipant(): void
    {
        $this->validate();

        try {
            $accessCode = Participant::generateAccessCode();

            Participant::create([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone,
                'access_code' => $accessCode,
                'numbers' => $this->selectedNumbers,
                'amount' => (float) $this->amount,
                'paid' => $this->paid,
                'paid_at' => $this->paid ? now() : null,
            ]);

            $this->toast()
                ->success('✅ Votação criada com sucesso!')
                ->send();

            $this->resetForm();
            $this->modal = false;
            $this->dispatch('participantCreated');
        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao criar votação. Tente novamente.')
                ->send();
        }
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'phone', 'email', 'selectedNumbers', 'amount', 'paid']);
        $this->amount = $this->defaultAmountProperty;
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function closeModal(): void
    {
        $this->modal = false;
    }

    public function isSelected(int $number): bool
    {
        return in_array($number, $this->selectedNumbers);
    }

    public function render()
    {
        return view('livewire.admin.create-participant');
    }
}
