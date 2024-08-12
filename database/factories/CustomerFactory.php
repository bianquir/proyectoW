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
            'id_message'=>fake()->numberBetween(),
            'tag_id'=>fake()->numberBetween('1', '10'),
            'order_id'=>fake()->numberBetween('1', '20'),
            'name'=>fake()->firstName(),
            'lastname'=>fake()->lastName(),
            'phone' => fake()->numberBetween(1000000000000, 9999999999999), 
            'email'=>fake()->email(),
        ];
    }
}
