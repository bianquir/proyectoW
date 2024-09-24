<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni'=>fake()->unique()->numberBetween('30000000', '50000000'),
            'cuil' => fake()->unique()->numberBetween('30000000000', '50000000'),
            'name'=>fake()->firstName(),
            'lastname'=>fake()->lastName(),
            'wa_id'=>fake()->unique()->numberBetween('100', '150'),
            'email'=>fake()->email(),
            'address' => fake()->address(),
            'created_at'=>now(),
            'updated_at' =>now()
        ];
    }
}
