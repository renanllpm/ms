<?php

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('can get setting value', function () {
    Setting::create(['key' => 'test_key', 'value' => 'test_value']);
    
    $value = Setting::get('test_key');
    
    expect($value)->toBe('test_value');
});

test('returns default value when setting does not exist', function () {
    $value = Setting::get('nonexistent_key', 'default');
    
    expect($value)->toBe('default');
});

test('can set setting value', function () {
    Setting::set('new_key', 'new_value');
    
    expect(Setting::where('key', 'new_key')->first()->value)->toBe('new_value');
});

test('updates existing setting', function () {
    Setting::create(['key' => 'existing_key', 'value' => 'old_value']);
    
    Setting::set('existing_key', 'new_value');
    
    expect(Setting::where('key', 'existing_key')->first()->value)->toBe('new_value');
});

test('setting is cached', function () {
    Setting::create(['key' => 'cached_key', 'value' => 'cached_value']);
    
    // Primeira chamada - busca do banco
    $value1 = Setting::get('cached_key');
    
    // Mudar diretamente no banco sem usar Setting::set()
    Setting::where('key', 'cached_key')->update(['value' => 'new_cached_value']);
    
    // Segunda chamada - deve retornar valor cacheado (antigo)
    $value2 = Setting::get('cached_key');
    
    expect($value1)->toBe('cached_value')
        ->and($value2)->toBe('cached_value'); // Ainda o valor cacheado
});

test('setting cache is cleared when updated', function () {
    Setting::create(['key' => 'update_key', 'value' => 'old_value']);
    
    Setting::get('update_key'); // Cacheia o valor
    
    Setting::set('update_key', 'new_value'); // Deve limpar o cache
    
    $value = Setting::get('update_key');
    
    expect($value)->toBe('new_value');
});

test('can clear all settings cache', function () {
    Setting::create(['key' => 'key1', 'value' => 'value1']);
    Setting::create(['key' => 'key2', 'value' => 'value2']);
    
    Setting::get('key1');
    Setting::get('key2');
    
    Setting::clearCache();
    
    // Cache deve estar limpo
    expect(Cache::has('setting_key1'))->toBeFalse()
        ->and(Cache::has('setting_key2'))->toBeFalse();
});

test('setting has fillable attributes', function () {
    $fillable = (new Setting())->getFillable();
    
    expect($fillable)->toContain('key')
        ->and($fillable)->toContain('value');
});
