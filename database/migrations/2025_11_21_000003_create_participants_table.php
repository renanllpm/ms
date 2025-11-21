<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('access_code')->unique(); // Senha gerada para acessar
            $table->json('numbers'); // Array com 6 números escolhidos
            $table->decimal('amount', 10, 2)->default(0); // Valor da aposta
            $table->boolean('paid')->default(false); // Se pagou ou não
            $table->string('payment_proof')->nullable(); // Caminho do comprovante
            $table->timestamp('paid_at')->nullable(); // Data do pagamento
            $table->text('notes')->nullable(); // Observações do admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
