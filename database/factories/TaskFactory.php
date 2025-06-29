<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(2),
            'data_limite' => $this->faker->optional(0.7)->dateTimeBetween('now', '+1 month'),
            'finalizado' => $this->faker->boolean(20), // 20% chance de ser true
        ];
    }
}
