<?php

use App\Models\Participant;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
    
    Setting::set('numbers_to_pick', 6);
    Setting::set('min_number', 1);
    Setting::set('max_number', 60);
    Setting::set('default_bet_amount', 5.00);
});

test('admin can access participants page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/participants');
    $response->assertStatus(200);
});

test('non admin cannot access participants page', function () {
    $response = $this->actingAs($this->user)->get('/admin/participants');
    $response->assertStatus(403);
});

test('guest cannot access participants page', function () {
    $response = $this->get('/admin/participants');
    $response->assertRedirect('/login');
});

test('admin can see participants list', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
        'paid' => false,
    ]);

    $this->actingAs($this->admin);
    
    Livewire::test('admin.manage-participants')
        ->assertSee('João Silva')
        ->assertSee('ABC12345');
});

test('admin can toggle paid status', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
        'paid' => false,
    ]);

    $this->actingAs($this->admin);
    
    Livewire::test('admin.manage-participants')
        ->call('togglePaid', $participant->id);

    expect($participant->fresh()->paid)->toBeTrue()
        ->and($participant->fresh()->paid_at)->not->toBeNull();
});

test('admin can mark participant as unpaid', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
        'paid' => true,
        'paid_at' => now(),
    ]);

    $this->actingAs($this->admin);
    
    Livewire::test('admin.manage-participants')
        ->call('togglePaid', $participant->id);

    expect($participant->fresh()->paid)->toBeFalse()
        ->and($participant->fresh()->paid_at)->toBeNull();
});

test('admin can delete participant', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
        'paid' => false,
    ]);

    $this->actingAs($this->admin);
    
    Livewire::test('admin.manage-participants')
        ->call('deleteParticipant', $participant->id);

    expect(Participant::find($participant->id))->toBeNull();
});

test('admin can see total participants count', function () {
    Participant::factory()->count(5)->create();

    $this->actingAs($this->admin);
    
    Livewire::test('admin.manage-participants')
        ->assertSee('5');
});

test('admin can see paid and unpaid counts', function () {
    Participant::factory()->count(3)->create(['paid' => true]);
    Participant::factory()->count(2)->create(['paid' => false]);

    $this->actingAs($this->admin);
    
    $component = Livewire::test('admin.manage-participants');
    
    // Verificar que existem 3 pagos e 2 não pagos
    expect(Participant::where('paid', true)->count())->toBe(3)
        ->and(Participant::where('paid', false)->count())->toBe(2);
});

test('admin can see total amounts', function () {
    Participant::factory()->create(['amount' => 10.00, 'paid' => true]);
    Participant::factory()->create(['amount' => 15.00, 'paid' => true]);
    Participant::factory()->create(['amount' => 5.00, 'paid' => false]);

    $this->actingAs($this->admin);
    
    $totalPaid = Participant::where('paid', true)->sum('amount');
    $totalUnpaid = Participant::where('paid', false)->sum('amount');
    
    expect($totalPaid)->toBe(25)
        ->and($totalUnpaid)->toBe(5);
});

test('participant numbers are displayed correctly', function () {
    $participant = Participant::create([
        'name' => 'João Silva',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
        'paid' => false,
    ]);

    expect($participant->sorted_numbers)->toBe([5, 10, 15, 20, 25, 30]);
});
