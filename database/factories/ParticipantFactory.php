<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gerar 6 números únicos aleatórios entre 1 e 60
        $numbers = collect(range(1, 60))->random(6)->sort()->values()->toArray();
        
        return [
            'name' => fake()->name(),
            'email' => fake()->optional()->email(),
            'phone' => fake()->optional()->phoneNumber(),
            'access_code' => strtoupper(fake()->bothify('????????')),
            'numbers' => $numbers,
            'amount' => fake()->randomFloat(2, 1, 50),
            'paid' => fake()->boolean(),
            'payment_proof' => null,
            'paid_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
