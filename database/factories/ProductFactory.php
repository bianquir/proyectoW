<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Listas de valores predefinidos
        $names = [
            'Arena gruesa', 'Cemento Portland', 'Grifería de baño', 'Pintura blanca', 
            'Revestimiento cerámico', 'Tubería de PVC', 'Ladrillos', 'Pala de obra', 
            'Llave inglesa', 'Mezcladora de cemento', 'Cinta métrica', 'Pintura para exteriores'
        ];

        $descriptions = [
            'Arena gruesa para construcción, ideal para mezcla de cemento.',
            'Cemento de alta resistencia para obras de construcción.',
            'Conjunto de grifos de baño, resistente a la corrosión.',
            'Pintura a base de agua para interiores, acabado mate.',
            'Baldosas cerámicas para pisos y paredes, resistentes al agua.',
            'Tubería de PVC para sistemas de plomería, fácil de cortar e instalar.',
            'Ladrillos rojos para construcción, de buena calidad.',
            'Pala metálica de alta resistencia para mover tierra y escombros.',
            'Llave ajustable de acero para trabajos de mecánica y fontanería.',
            'Mezcladora eléctrica para preparar cemento y mortero.',
            'Cinta métrica de 5 metros, con bloqueo y fácil lectura.',
            'Pintura resistente a la intemperie, ideal para exteriores.'
        ];

        $prices = [
            5000, 8000, 2000, 900, 2800, 4300, 1500, 1500, 2560, 5750, 1200, 8000
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'description' => $this->faker->randomElement($descriptions),
            'price' => $this->faker->randomElement($prices),
        ];
    }
}
