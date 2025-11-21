<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::firstOrCreate(
            ['email' => 'renanllpm@gmail.com'],
            [
                'name' => 'Renan Lima',
                'email' => 'renanllpm@gmail.com',
                'password' => Hash::make('mudar@123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Criar configurações padrão
        Setting::firstOrCreate(
            ['key' => 'numbers_to_pick'],
            ['value' => '6']
        );

        Setting::firstOrCreate(
            ['key' => 'min_number'],
            ['value' => '1']
        );

        Setting::firstOrCreate(
            ['key' => 'max_number'],
            ['value' => '60']
        );

        Setting::firstOrCreate(
            ['key' => 'default_bet_amount'],
            ['value' => '5.00']
        );
    }
}
