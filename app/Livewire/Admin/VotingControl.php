<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class VotingControl extends Component
{
    use Interactions;

    public bool $modal = false;
    public string $messagePreview = '';

    #[Computed]
    public function votingStatus(): string
    {
        return Setting::get('voting_status', 'open');
    }

    #[Computed]
    public function isVotingOpen(): bool
    {
        return $this->votingStatus === 'open';
    }

    public function toggleVotingStatus(): void
    {
        $currentStatus = Setting::get('voting_status', 'open');
        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';

        Setting::set('voting_status', $newStatus);

        $message = $newStatus === 'closed'
            ? '🔒 Votação encerrada com sucesso!'
            : '🔓 Votação reabierta com sucesso!';

        $this->toast()->success($message)->send();
    }

    /**
     * Compila dados da votação para gerar mensagem WhatsApp
     */
    private function compileVotingData(): array
    {
        $participants = Participant::all();
        $totalParticipants = $participants->count();

        // Contar pagos e não pagos
        $paidCount = $participants->where('paid', true)->count();
        $unpaidCount = $totalParticipants - $paidCount;

        // Calcular frequência de números
        $frequency = array_fill(1, 60, 0);
        foreach ($participants as $participant) {
            foreach ($participant->numbers as $number) {
                if ($number >= 1 && $number <= 60) {
                    $frequency[$number]++;
                }
            }
        }

        // Ordenar por frequência (decrescente)
        arsort($frequency);

        // Pegar top 6 números mais escolhidos
        $topNumbers = array_slice($frequency, 0, Setting::get('numbers_to_pick', 6), true);

        return [
            'totalParticipants' => $totalParticipants,
            'paidCount' => $paidCount,
            'paidValue' => $paidCount * Setting::get('default_bet_amount', 5.00),
            'unpaidCount' => $unpaidCount,
            'topNumbers' => $topNumbers,
            'totalVotes' => $totalParticipants,
            'frequency' => $frequency,
        ];
    }

    /**
     * Calcula percentual de votos para um número
     */
    private function getPercentage(int $count, int $total): float
    {
        if ($total === 0) {
            return 0;
        }
        return round(($count / $total) * 100, 1);
    }

    /**
     * Gera mensagem formatada para WhatsApp
     */
    public function generateWhatsAppMessage(): string
    {
        $data = $this->compileVotingData();

        $message = "🎰 *RESULTADO DA VOTAÇÃO* 🎰\n\n";

        $message .= "📊 *RESUMO DE PARTICIPANTES*\n";
        $message .= "Total: {$data['totalParticipants']}\n";
        $message .= "✅ Valor total: " . number_format($data['paidValue'], 2, ',', '.') . "\n";

        $message .= "🔢 *NÚMEROS QUE SERÃO JOGADOS*\n";
        $message .= "(Mais votados pela grupo)\n\n";

        $sortedNumbers = [];


        foreach ($data['frequency'] as $number => $count) {
            $percentage = $this->getPercentage($count, $data['totalVotes']);
            if (array_key_exists($number, $data['topNumbers']) === true) {
                $sortedNumbers[] = $number;
                $message .= "✅";
                $message .= sprintf("%02d ➜ %d votos (%s%%)", $number, $count, $percentage);
                $message .= "\n";
            } else {
                $message .= sprintf("%02d ➜ %d votos (%s%%)\n", $number, $count, $percentage);
            }
        }


        $message .= "\n🎯 *JOGO FINAL:* ";
        $message .= implode(" - ", array_map(fn($n) => sprintf("%02d", $n), $sortedNumbers));

        $message .= "\n\n💡 Este será o jogo que o grupo vai jogar na próxima Mega-Sena!";
        $message .= "\n\nBoa sorte! 🍀";

        return $message;
    }

    /**
     * Copia mensagem para área de transferência e exibe
     */
    public function copyWhatsAppMessage(): void
    {
        $message = $this->generateWhatsAppMessage();
        $this->messagePreview = $message;
    }

    /**
     * Confirma cópia da mensagem
     */
    public function confirmCopyMessage(): void
    {
        $message = $this->messagePreview;

        $this->dispatch('copy-whatsapp-message', message: $message);

        $this->showMessagePreview = false;

        $this->toast()
            ->success('✅ Mensagem copiada!', 'Cole no WhatsApp para enviar.')
            ->send();
    }

    /**
     * Fecha o modal de preview
     */
    public function closeMessagePreview(): void
    {
        $this->showMessagePreview = false;
    }

    public function render()
    {
        return view('livewire.admin.voting-control');
    }

    public function mount(): void
    {
        $this->copyWhatsAppMessage();
    }
}
