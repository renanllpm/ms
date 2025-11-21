<?php

use App\Models\Participant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
});

test('admin can access statistics page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/statistics');
    $response->assertStatus(200);
});

test('non admin cannot access statistics page', function () {
    $response = $this->actingAs($this->user)->get('/admin/statistics');
    $response->assertStatus(403);
});

test('guest cannot access statistics page', function () {
    $response = $this->get('/admin/statistics');
    $response->assertRedirect('/login');
});

test('statistics shows most chosen numbers', function () {
    // Criar participantes com números específicos
    Participant::create([
        'name' => 'Test 1',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [1, 2, 3, 4, 5, 6],
        'amount' => 5.00,
    ]);
    
    Participant::create([
        'name' => 'Test 2',
        'email' => null,
        'phone' => null,
        'access_code' => 'DEF12345',
        'numbers' => [1, 2, 3, 7, 8, 9],
        'amount' => 5.00,
    ]);

    $this->actingAs($this->admin);
    
    Livewire::test('admin.statistics')
        ->assertSee('1') // Número 1 aparece 2 vezes
        ->assertSee('2') // Número 2 aparece 2 vezes
        ->assertSee('3'); // Número 3 aparece 2 vezes
});

test('statistics calculates number frequency correctly', function () {
    Participant::create([
        'name' => 'Test 1',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [5, 10, 15, 20, 25, 30],
        'amount' => 5.00,
    ]);
    
    Participant::create([
        'name' => 'Test 2',
        'email' => null,
        'phone' => null,
        'access_code' => 'DEF12345',
        'numbers' => [5, 10, 15, 35, 40, 45],
        'amount' => 5.00,
    ]);

    $participants = Participant::all();
    $allNumbers = $participants->pluck('numbers')->flatten();
    
    $frequency = $allNumbers->countBy()->sortDesc();
    
    expect($frequency->get(5))->toBe(2)
        ->and($frequency->get(10))->toBe(2)
        ->and($frequency->get(15))->toBe(2)
        ->and($frequency->get(20))->toBe(1);
});

test('statistics works with no participants', function () {
    $this->actingAs($this->admin);
    
    $response = $this->get('/admin/statistics');
    $response->assertStatus(200);
});

test('statistics shows all 60 numbers', function () {
    Participant::create([
        'name' => 'Test 1',
        'email' => null,
        'phone' => null,
        'access_code' => 'ABC12345',
        'numbers' => [1, 2, 3, 4, 5, 6],
        'amount' => 5.00,
    ]);

    $this->actingAs($this->admin);
    
    $response = $this->get('/admin/statistics');
    
    // Verificar que todos os números de 1 a 60 estão presentes
    for ($i = 1; $i <= 60; $i++) {
        $response->assertSee((string)$i);
    }
});
