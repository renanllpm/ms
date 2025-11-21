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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });

        // Inserir valores padrão
        DB::table('settings')->insert([
            ['key' => 'numbers_to_pick', 'value' => '6', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_number', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_number', 'value' => '60', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_bet_amount', 'value' => '5.00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
