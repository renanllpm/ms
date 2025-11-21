<?php

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('participant has correct fillable attributes', function () {
    $fillable = (new Participant())->getFillable();
    
    expect($fillable)->toContain('name')
        ->and($fillable)->toContain('email')
        ->and($fillable)->toContain('phone')
        ->and($fillable)->toContain('access_code')
        ->and($fillable)->toContain('numbers')
        ->and($fillable)->toContain('amount')
        ->and($fillable)->toContain('paid')
        ->and($fillable)->toContain('payment_proof')
        ->and($fillable)->toContain('paid_at');
});

test('numbers are cast to array', function () {
    $participant = Participant::make([
        'numbers' => [1, 2, 3, 4, 5, 6]
    ]);
    
    expect($participant->numbers)->toBeArray();
});

test('paid is cast to boolean', function () {
    $participant = Participant::make([
        'paid' => 1
    ]);
    
    expect($participant->paid)->toBeBool();
});

test('amount is cast to float', function () {
    $participant = Participant::make([
        'amount' => '10.50'
    ]);
    
    expect($participant->amount)->toBeString()
        ->and($participant->amount)->toBe('10.50');
});

test('generates unique access code', function () {
    $code1 = Participant::generateAccessCode();
    $code2 = Participant::generateAccessCode();
    
    expect($code1)->toHaveLength(8)
        ->and($code2)->toHaveLength(8)
        ->and($code1)->not->toBe($code2);
});

test('can mark as paid', function () {
    $participant = Participant::factory()->create(['paid' => false]);
    
    $participant->markAsPaid();
    
    expect($participant->fresh()->paid)->toBeTrue()
        ->and($participant->fresh()->paid_at)->not->toBeNull();
});

test('can mark as unpaid', function () {
    $participant = Participant::factory()->create([
        'paid' => true,
        'paid_at' => now()
    ]);
    
    $participant->markAsUnpaid();
    
    expect($participant->fresh()->paid)->toBeFalse()
        ->and($participant->fresh()->paid_at)->toBeNull();
});

test('sorted numbers accessor returns sorted array', function () {
    $participant = Participant::make([
        'numbers' => [30, 10, 50, 5, 20, 15]
    ]);
    
    expect($participant->sorted_numbers)->toBe([5, 10, 15, 20, 30, 50]);
});

test('formatted phone accessor formats correctly', function () {
    $participant = Participant::make([
        'phone' => '11999887766'
    ]);
    
    expect($participant->formatted_phone)->toBeString();
});
