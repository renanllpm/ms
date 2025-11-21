<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações da Mega-Sena
    |--------------------------------------------------------------------------
    */

    // Quantidade de números que podem ser escolhidos
    'numbers_to_pick' => env('MEGASENA_NUMBERS_TO_PICK', 6),

    // Número mínimo disponível
    'min_number' => env('MEGASENA_MIN_NUMBER', 1),

    // Número máximo disponível
    'max_number' => env('MEGASENA_MAX_NUMBER', 60),

    // Valor padrão da aposta (em reais)
    'default_bet_amount' => env('MEGASENA_DEFAULT_BET_AMOUNT', 5.00),

    // Valor mínimo da aposta
    'min_bet_amount' => env('MEGASENA_MIN_BET_AMOUNT', 1.00),

    // Permitir comprovante opcional
    'allow_optional_proof' => env('MEGASENA_ALLOW_OPTIONAL_PROOF', true),

    // Tamanho máximo do comprovante em KB
    'max_proof_size' => env('MEGASENA_MAX_PROOF_SIZE', 5120), // 5MB
];
