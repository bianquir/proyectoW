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
            'dni'=>fake()->numberBetween('1000000', '50000000'),
            'Cuil'=>fake()->numerify('##-########-##'),
            'id_message'=>fake()->numberBetween(),
            'tag_id'=>fake()->numberBetween('1', '10'),
            'order_id'=>fake()->numberBetween('1', '20'),
            'name'=>fake()->firstName(),
            'lastname'=>fake()->lastName(),
            'phone'=>fake()->phoneNumber(),
            'email'=>fake()->email(),
        ];
    }
}
