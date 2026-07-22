<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('119########'),
            'email' => fake()->safeEmail(),
            'insurance_type' => fake()->randomElement(['auto', 'residencial', 'vida', 'saude', 'empresarial', 'viagem', 'previdencia']),
            'status' => 'novo',
            'source' => fake()->randomElement(['indicacao', 'whatsapp', 'instagram', 'site']),
        ];
    }
}
