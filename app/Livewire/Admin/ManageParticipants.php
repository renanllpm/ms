<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class ManageParticipants extends Component
{
    use Interactions;
    use WithPagination;

    public ?string $search = null;
    public string $filterStatus = 'all'; // all, paid, unpaid
    
    public function togglePaid(int $participantId): void
    {
        $participant = Participant::findOrFail($participantId);
        
        if ($participant->paid) {
            $participant->markAsUnpaid();
            $message = "Participante '{$participant->name}' marcado como NÃO PAGO.";
        } else {
            $participant->markAsPaid();
            $message = "Participante '{$participant->name}' marcado como PAGO!";
        }
        
        $this->toast()
            ->success($message)
            ->send();
    }

    public function deleteParticipant(int $participantId): void
    {
        $participant = Participant::findOrFail($participantId);
        $name = $participant->name;
        
        // Deletar comprovante se existir
        if ($participant->payment_proof) {
            \Storage::disk('public')->delete($participant->payment_proof);
        }
        
        $participant->delete();
        
        $this->toast()
            ->success("Participante '{$name}' removido com sucesso!")
            ->send();
    }

    public function getParticipantsProperty()
    {
        $query = Participant::query()
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('access_code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus === 'paid', fn($q) => $q->paid())
            ->when($this->filterStatus === 'unpaid', fn($q) => $q->unpaid())
            ->orderBy('created_at', 'desc');
        
        return $query->paginate(10);
    }

    public function getStatsProperty()
    {
        return [
            'total' => Participant::count(),
            'paid' => Participant::paid()->count(),
            'unpaid' => Participant::unpaid()->count(),
            'total_amount' => Participant::sum('amount'),
            'paid_amount' => Participant::paid()->sum('amount'),
            'unpaid_amount' => Participant::unpaid()->sum('amount'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.manage-participants', [
            'participants' => $this->participants,
            'stats' => $this->stats,
        ]);
    }
}
