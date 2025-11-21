<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
});

test('admin can access users page', function () {
    $response = $this->actingAs($this->admin)->get('/users');
    $response->assertStatus(200);
});

test('non admin cannot access users page', function () {
    $response = $this->actingAs($this->user)->get('/users');
    $response->assertStatus(403);
});

test('guest cannot access users page', function () {
    $response = $this->get('/users');
    $response->assertRedirect('/login');
});

test('admin can see users list', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('users.index')
        ->assertSee('Name')
        ->assertSee('E-mail')
        ->assertSee('Admin');
});

test('admin can promote user to admin', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('users.index')
        ->call('toggleAdmin', $this->user->id);

    expect($this->user->fresh()->is_admin)->toBeTrue();
});

test('admin can demote admin to user', function () {
    $anotherAdmin = User::factory()->create(['is_admin' => true]);
    
    $this->actingAs($this->admin);
    
    Livewire::test('users.index')
        ->call('toggleAdmin', $anotherAdmin->id);

    expect($anotherAdmin->fresh()->is_admin)->toBeFalse();
});

test('admin cannot demote themselves', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('users.index')
        ->call('toggleAdmin', $this->admin->id);

    expect($this->admin->fresh()->is_admin)->toBeTrue();
});

test('only admin middleware blocks non admins', function () {
    $response = $this->actingAs($this->user)
        ->get('/admin/statistics');
    
    $response->assertStatus(403);
});

test('authenticated users can access dashboard', function () {
    $response = $this->actingAs($this->user)->get('/dashboard');
    $response->assertStatus(200);
});

test('guests cannot access dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});
