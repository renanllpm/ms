<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MegasenaChoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MegasenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@megasena.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        $this->command->info('✅ Usuário administrador criado!');
        $this->command->info('   Email: admin@megasena.com');
        $this->command->info('   Senha: admin123');

        // Criar usuários de teste
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Criar escolhas aleatórias para alguns usuários
            $numbers = [];
            while (count($numbers) < 6) {
                $num = rand(1, 60);
                if (!in_array($num, $numbers)) {
                    $numbers[] = $num;
                }
            }
            sort($numbers);

            MegasenaChoice::create([
                'user_id' => $user->id,
                'numbers' => $numbers,
            ]);
        }

        $this->command->info('✅ Usuários e escolhas de teste criados com sucesso!');
        $this->command->info('   Use: email@example.com / password para fazer login');
    }
}
