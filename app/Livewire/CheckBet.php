<?php

namespace App\Livewire;

use App\Models\Participant;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class CheckBet extends Component
{
    use Interactions;

    public ?string $accessCode = null;
    public ?Participant $participant = null;
    public bool $searched = false;

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
        $this->reset(['accessCode', 'participant', 'searched']);
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
