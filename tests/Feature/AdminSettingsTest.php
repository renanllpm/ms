<?php

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

test('admin can access settings page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/settings');
    $response->assertStatus(200);
});

test('non admin cannot access settings page', function () {
    $response = $this->actingAs($this->user)->get('/admin/settings');
    $response->assertStatus(403);
});

test('guest cannot access settings page', function () {
    $response = $this->get('/admin/settings');
    $response->assertRedirect('/login');
});

test('admin can update numbers to pick', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('numbersToPickProperty', 8)
        ->call('save');

    expect(Setting::get('numbers_to_pick'))->toBe('8');
});

test('admin can update number range', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('minNumberProperty', 1)
        ->set('maxNumberProperty', 80)
        ->call('save');

    expect(Setting::get('min_number'))->toBe('1')
        ->and(Setting::get('max_number'))->toBe('80');
});

test('admin can update bet amount', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('defaultBetAmount', 15.50)
        ->call('save');

    expect(Setting::get('default_bet_amount'))->toBe('15.5');
});

test('cannot set min number greater than max number', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('minNumberProperty', 50)
        ->set('maxNumberProperty', 40)
        ->call('save');

    expect(Setting::get('min_number'))->toBe('1');
});

test('cannot set numbers to pick greater than available range', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('numbersToPickProperty', 100)
        ->set('minNumberProperty', 1)
        ->set('maxNumberProperty', 60)
        ->call('save');

    expect(Setting::get('numbers_to_pick'))->toBe('6');
});

test('admin can reload settings', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('numbersToPickProperty', 10)
        ->call('loadSettings')
        ->assertSet('numbersToPickProperty', 6);
});

test('settings must have valid values', function () {
    $this->actingAs($this->admin);
    
    Livewire::test('admin.settings')
        ->set('numbersToPickProperty', -1)
        ->set('defaultBetAmount', 0)
        ->call('save')
        ->assertHasErrors(['numbersToPickProperty', 'defaultBetAmount']);
});
