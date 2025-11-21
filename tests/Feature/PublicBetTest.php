<?php

use App\Models\Participant;
use App\Models\Setting;
use Livewire\Livewire;

beforeEach(function () {
    // Criar configurações padrão
    Setting::set('numbers_to_pick', 6);
    Setting::set('min_number', 1);
    Setting::set('max_number', 60);
    Setting::set('default_bet_amount', 5.00);
});

test('public bet page is accessible', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('can select numbers', function () {
    Livewire::test('public-bet')
        ->call('toggleNumber', 5)
        ->call('toggleNumber', 10)
        ->call('toggleNumber', 15)
        ->assertSet('selectedNumbers', [5, 10, 15]);
});

test('cannot select more than configured numbers', function () {
    Livewire::test('public-bet')
        ->call('toggleNumber', 1)
        ->call('toggleNumber', 2)
        ->call('toggleNumber', 3)
        ->call('toggleNumber', 4)
        ->call('toggleNumber', 5)
        ->call('toggleNumber', 6)
        ->call('toggleNumber', 7)
        ->assertCount('selectedNumbers', 6);
});

test('can deselect number', function () {
    Livewire::test('public-bet')
        ->call('toggleNumber', 5)
        ->call('toggleNumber', 10)
        ->assertCount('selectedNumbers', 2)
        ->call('toggleNumber', 5)
        ->assertCount('selectedNumbers', 1)
        ->assertSet('selectedNumbers', [10]);
});

test('can generate random numbers', function () {
    Livewire::test('public-bet')
        ->call('generateRandom')
        ->assertCount('selectedNumbers', 6);
});

test('can clear selection', function () {
    Livewire::test('public-bet')
        ->call('toggleNumber', 5)
        ->call('toggleNumber', 10)
        ->assertCount('selectedNumbers', 2)
        ->call('clearSelection')
        ->assertCount('selectedNumbers', 0);
});

test('can submit bet with valid data', function () {
    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [5, 10, 15, 20, 25, 30])
        ->call('submitBet')
        ->assertSet('showSuccess', true);

    expect(Participant::where('name', 'João Silva')->exists())->toBeTrue();
});

test('cannot submit bet without name', function () {
    Livewire::test('public-bet')
        ->set('name', '')
        ->set('selectedNumbers', [5, 10, 15, 20, 25, 30])
        ->call('submitBet')
        ->assertHasErrors(['name']);
});

test('cannot submit bet without enough numbers', function () {
    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [5, 10, 15])
        ->call('submitBet')
        ->assertHasErrors(['selectedNumbers']);
});

test('access code is unique', function () {
    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [5, 10, 15, 20, 25, 30])
        ->call('submitBet');

    Livewire::test('public-bet')
        ->set('name', 'Maria Santos')
        ->set('selectedNumbers', [1, 2, 3, 4, 5, 6])
        ->call('submitBet');

    $participants = Participant::all();
    expect($participants[0]->access_code)->not->toBe($participants[1]->access_code);
});

test('can start new bet after success', function () {
    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [5, 10, 15, 20, 25, 30])
        ->call('submitBet')
        ->assertSet('showSuccess', true)
        ->call('newBet')
        ->assertSet('showSuccess', false)
        ->assertSet('name', '')
        ->assertCount('selectedNumbers', 0);
});

test('bet amount comes from settings', function () {
    Setting::set('default_bet_amount', 10.50);

    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [5, 10, 15, 20, 25, 30])
        ->call('submitBet');

    expect(Participant::first()->amount)->toBe('10.50');
});

test('numbers are sorted in database', function () {
    Livewire::test('public-bet')
        ->set('name', 'João Silva')
        ->set('selectedNumbers', [30, 5, 20, 10, 25, 15])
        ->call('submitBet');

    $numbers = Participant::first()->numbers;
    $sortedNumbers = $numbers;
    sort($sortedNumbers);
    
    expect($numbers)->toBe($sortedNumbers);
});
