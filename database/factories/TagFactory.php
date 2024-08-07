<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_tag'=>fake()->name(),
            'description'=>fake()->text(50),
            'color'=>fake()->unique()->hexColor(),
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
