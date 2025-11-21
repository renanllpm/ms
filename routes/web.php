<?php

use App\Livewire\User\Profile;
use App\Livewire\PublicBet;
use App\Livewire\CheckBet;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index;

// Página pública de apostas (sem autenticação)
Route::get('/', PublicBet::class)->name('public.bet');
Route::get('/apostar', PublicBet::class)->name('bet');
Route::get('/consultar', CheckBet::class)->name('check.bet');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::view('/participantes', 'participants')->name('participants.index');

    Route::get('/user/profile', Profile::class)->name('user.profile');
});

// Rotas Administrativas
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/statistics', 'admin.statistics')->name('statistics');
    Route::view('/participants', 'admin.participants')->name('participants');
    Route::view('/settings', 'admin.settings')->name('settings');
});

// Gerenciamento de Usuários (apenas para admins)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', Index::class)->name('users.index');
});

require __DIR__.'/auth.php';
