<?php

namespace App\Livewire;

use App\Models\Participant;
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

        if (!$this->participant) {
            $this->toast()
                ->error('Código não encontrado', 'Verifique se digitou corretamente.')
                ->send();
        }
    }

    public function clear(): void
    {
        $this->reset(['accessCode', 'participant', 'searched', 'paymentProof', 'phone']);
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
