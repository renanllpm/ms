<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MegasenaChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numbers',
    ];

    protected $casts = [
        'numbers' => 'array',
    ];

    /**
     * Relação com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Validação customizada dos números
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($choice) {
            // Garantir que os números estão ordenados
            if (is_array($choice->numbers)) {
                $numbers = $choice->numbers;
                sort($numbers);
                $choice->numbers = $numbers;
            }
        });
    }

    /**
     * Retorna os números ordenados
     */
    public function getSortedNumbersAttribute(): array
    {
        $numbers = $this->numbers;
        sort($numbers);
        return $numbers;
    }
}
