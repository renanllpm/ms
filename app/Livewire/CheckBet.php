<?php

namespace App\Livewire;

use App\Models\Participant;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class CheckBet extends Component
{
    use Interactions;
    use WithFileUploads;

    public ?string $accessCode = null;
    public ?Participant $participant = null;
    public bool $searched = false;
    public $paymentProof = null;
    public string $phone = '';
    public array $selectedNumbers = [];
    public bool $editingNumbers = false;

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

    public function rules(): array
    {
        return [
            'accessCode' => ['required', 'string', 'size:8'],
        ];
    }

    public function search(): void
    {
        $this->validate();

        $this->participant = Participant::where('access_code', strtoupper($this->accessCode))->first();
        $this->searched = true;

        if ($this->participant) {
            $this->selectedNumbers = $this->participant->numbers;
        }

        if (!$this->participant) {
            $this->toast()
                ->error('Código não encontrado', 'Verifique se digitou corretamente.')
                ->send();
        }
    }

    public function clear(): void
    {
        $this->reset(['accessCode', 'participant', 'searched', 'paymentProof', 'phone', 'selectedNumbers', 'editingNumbers']);
    }

    /**
     * Atualiza o telefone do participante se ainda não estiver preenchido
     */
    public function updatePhone(): void
    {
        if (!$this->participant) {
            $this->toast()
                ->error('Participante não encontrado')
                ->send();
            return;
        }

        if ($this->participant->phone) {
            $this->toast()
                ->warning('Telefone já foi preenchido', 'Você já informou um telefone anteriormente.')
                ->send();
            return;
        }

        $this->validate([
            'phone' => ['required', 'string', 'min:10', 'max:20'],
        ], [
            'phone.required' => 'Telefone é obrigatório',
            'phone.min' => 'Telefone deve ter no mínimo 10 caracteres',
            'phone.max' => 'Telefone deve ter no máximo 20 caracteres',
        ]);

        try {
            $this->participant->update(['phone' => $this->phone]);
            $this->phone = '';
            $this->toast()
                ->success('✅ Telefone atualizado com sucesso!')
                ->send();
        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao atualizar telefone. Tente novamente.')
                ->send();
        }
    }

    /**
     * Envia o comprovante para um participante
     */
    public function uploadPaymentProof(): void
    {
        if (!$this->participant) {
            $this->toast()
                ->error('Participante não encontrado')
                ->send();
            return;
        }

        if ($this->participant->payment_proof) {
            $this->toast()
                ->warning('Comprovante já foi enviado', 'Você já enviou um comprovante anteriormente.')
                ->send();
            return;
        }

        $this->validate([
            'paymentProof' => ['required', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
        ], [
            'paymentProof.required' => 'Selecione um arquivo',
            'paymentProof.mimes' => 'O arquivo deve ser JPG, PNG ou PDF',
            'paymentProof.max' => 'O arquivo não pode exceder 5MB',
        ]);

        try {
            $path = $this->paymentProof->store('payment-proofs', 'public');
            $this->participant->update(['payment_proof' => $path]);

            $this->paymentProof = null;
            $this->toast()
                ->success('✅ Comprovante enviado com sucesso!')
                ->send();
        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao enviar comprovante. Tente novamente.')
                ->send();
        }
    }

    /**
     * Inicia edição de números
     */
    public function startEditingNumbers(): void
    {
        if (!$this->participant || !$this->participant->abstained) {
            return;
        }

        $this->editingNumbers = true;
        $this->selectedNumbers = [];
    }

    /**
     * Toggle número na edição
     */
    public function toggleNumberEdit(int $number): void
    {
        if (!$this->editingNumbers) {
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

    /**
     * Salvar números escolhidos
     */
    public function saveNumbers(): void
    {
        if (!$this->participant || !$this->editingNumbers) {
            $this->toast()
                ->error('Erro ao salvar números')
                ->send();
            return;
        }

        if (count($this->selectedNumbers) !== $this->numbersToPickProperty) {
            $this->toast()
                ->warning("Selecione exatamente {$this->numbersToPickProperty} números")
                ->send();
            return;
        }

        try {
            $this->participant->update([
                'numbers' => $this->selectedNumbers,
                'abstained' => false,
            ]);

            $this->editingNumbers = false;
            $this->toast()
                ->success('✅ Números salvos com sucesso!')
                ->send();
        } catch (\Exception $e) {
            $this->toast()
                ->error('❌ Erro ao salvar números. Tente novamente.')
                ->send();
        }
    }

    /**
     * Cancelar edição de números
     */
    public function cancelEditNumbers(): void
    {
        $this->editingNumbers = false;
        $this->selectedNumbers = $this->participant->numbers;
    }

    /**
     * Retorna os números mais escolhidos por todos os participantes
     */
    public function getMostChosenNumbers(): array
    {
        $participants = Participant::all();
        $numberFrequency = [];

        foreach ($participants as $participant) {
            foreach ($participant->numbers as $number) {
                if (!isset($numberFrequency[$number])) {
                    $numberFrequency[$number] = 0;
                }
                $numberFrequency[$number]++;
            }
        }

        arsort($numberFrequency);
        return array_slice($numberFrequency, 0, 10, true);
    }

    /**
     * Verifica quais números do usuário estão entre os mais escolhidos
     */
    public function getMatchingNumbers(): array
    {
        if (!$this->participant) {
            return [];
        }

        $mostChosen = array_keys($this->getMostChosenNumbers());
        $userNumbers = $this->participant->numbers;

        return array_values(array_intersect($userNumbers, $mostChosen));
    }

    public function render()
    {
        $mostChosenNumbers = $this->getMostChosenNumbers();
        $matchingNumbers = $this->getMatchingNumbers();

        return view('livewire.check-bet', [
            'mostChosenNumbers' => $mostChosenNumbers,
            'matchingNumbers' => $matchingNumbers,
        ])->layout('layouts.public');
    }
}
