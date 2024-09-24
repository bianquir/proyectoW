<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'customer_id'=>fake()->numberBetween('1', '10'),
             'shipping_day'=> fake()->date(),
             'state' =>fake()->randomElement(['Entregado', 'En proceso', 'En espera']),
             'created_at'=>now(),
             'updated_at' =>now()
        ];
    }
}
