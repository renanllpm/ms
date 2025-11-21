<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'access_code',
        'numbers',
        'amount',
        'paid',
        'payment_proof',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'numbers' => 'array',
        'amount' => 'decimal:2',
        'paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    /**
     * Gerar código de acesso único
     */
    public static function generateAccessCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('access_code', $code)->exists());

        return $code;
    }

    /**
     * Retorna os números ordenados
     */
    public function getSortedNumbersAttribute(): array
    {
        $numbers = $this->numbers ?? [];
        sort($numbers);
        return $numbers;
    }

    /**
     * Formatar telefone
     */
    public function getFormattedPhoneAttribute(): string
    {
        if (!$this->phone) {
            return '';
        }
        
        $phone = preg_replace('/\D/', '', $this->phone);
        
        if (strlen($phone) === 11) {
            return sprintf('(%s) %s-%s', 
                substr($phone, 0, 2),
                substr($phone, 2, 5),
                substr($phone, 7)
            );
        }
        
        return $this->phone;
    }

    /**
     * Marcar como pago
     */
    public function markAsPaid(): void
    {
        $this->update([
            'paid' => true,
            'paid_at' => now(),
        ]);
    }

    /**
     * Marcar como não pago
     */
    public function markAsUnpaid(): void
    {
        $this->update([
            'paid' => false,
            'paid_at' => null,
        ]);
    }

    /**
     * Escopo para participantes pagos
     */
    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    /**
     * Escopo para participantes não pagos
     */
    public function scopeUnpaid($query)
    {
        return $query->where('paid', false);
    }
}
