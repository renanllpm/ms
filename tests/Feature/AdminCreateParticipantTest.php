<?php

use App\Models\Participant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

test('admin can create a participant manually', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.create-participant')
        ->set('name', 'João Silva')
        ->set('phone', '11999999999')
        ->set('email', 'joao@example.com')
        ->set('selectedNumbers', [1, 2, 3, 4, 5, 6])
        ->set('amount', '10.00')
        ->set('paid', true)
        ->call('createParticipant');

    expect(Participant::count())->toBe(1);

    $participant = Participant::first();
    expect($participant->name)->toBe('João Silva')
        ->and($participant->phone)->toBe('11999999999')
        ->and($participant->paid)->toBeTrue()
        ->and(count($participant->numbers))->toBe(6);
});

test('admin can share participant via whatsapp', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'phone' => '11999999999',
        'access_code' => 'ABC12345',
        'numbers' => [1, 2, 3, 4, 5, 6],
        'amount' => 5.00,
        'paid' => false,
    ]);

    $this->actingAs($this->admin);

    Livewire::test('admin.manage-participants')
        ->call('shareViaWhatsApp', $participant->id)
        ->assertDispatched('redirect');
});

test('selected numbers must match required count', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.create-participant')
        ->set('name', 'João Silva')
        ->set('phone', '11999999999')
        ->set('email', 'joao@example.com')
        ->set('selectedNumbers', [1, 2, 3]) // Only 3 numbers instead of 6
        ->set('amount', '10.00')
        ->call('createParticipant')
        ->assertHasErrors('selectedNumbers');
});

test('participant creation validates required fields', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.create-participant')
        ->call('createParticipant')
        ->assertHasErrors(['name', 'phone', 'selectedNumbers']);
});
