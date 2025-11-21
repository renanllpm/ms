<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'megasena:make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Torna um usuário administrador pelo email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Usuário com email '{$email}' não encontrado!");
            
            $create = $this->confirm('Deseja criar um novo usuário administrador?');
            
            if ($create) {
                $name = $this->ask('Nome do usuário');
                $password = $this->secret('Senha');
                
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'is_admin' => true,
                ]);
                
                $this->info("✅ Usuário administrador criado com sucesso!");
                $this->info("📧 Email: {$user->email}");
                $this->info("👤 Nome: {$user->name}");
                
                return Command::SUCCESS;
            }
            
            return Command::FAILURE;
        }
        
        if ($user->is_admin) {
            $this->warning("⚠️  O usuário '{$user->name}' já é administrador!");
            return Command::SUCCESS;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("✅ Usuário '{$user->name}' agora é administrador!");
        $this->info("📧 Email: {$user->email}");
        
        return Command::SUCCESS;
    }
}

