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
        // Lista de nombres de etiquetas con los nuevos estados
        $names = [
            'Pedido Pagado', 'En Espera', 'Cliente Nuevo', 'Cliente en Espera de Pago', 
            'Pedido Enviado', 'Pedido Cancelado', 'Pago Completo', 'Pedido Pendiente', 
            'Cliente Activo', 'Cliente Inactivo', 'Falta Información', 'Cliente Recurrente',
            'Enviado', 'En Proceso', 'Cliente Destacado', 'Proveedor', 'Pedido Completado', 'Cliente Premium'
        ];

        // Descripciones asociadas a cada estado
        $descriptions = [
            'El cliente ha pagado el pedido, está listo para ser enviado.',
            'El pedido está en espera de ser procesado o de que el cliente haga el pago.',
            'El cliente acaba de registrarse y su primer pedido está siendo procesado.',
            'El cliente aún no ha completado el pago del pedido.',
            'El pedido ya ha sido enviado y está en tránsito.',
            'El pedido fue cancelado por el cliente o por otro motivo.',
            'El cliente ha realizado el pago completo de su pedido.',
            'El pedido está pendiente de confirmación o en espera de algún detalle.',
            'El cliente está activo, realizando compras frecuentes.',
            'El cliente está inactivo y no ha realizado compras recientes.',
            'El cliente no ha proporcionado toda la información necesaria para procesar el pedido.',
            'El cliente realiza compras de manera recurrente, siempre compra productos específicos.',
            'El pedido ha sido enviado y está en camino.',
            'El pedido está en proceso de ser preparado y enviado.',
            'Cliente destacado debido a sus compras frecuentes y lealtad.',
            'Proveedor registrado para suministrar productos a la tienda.',
            'El pedido se completó exitosamente y se entregó al cliente.',
            'Cliente premium con acceso a ofertas exclusivas y beneficios.'
        ];

        // Colores predefinidos para cada etiqueta
        $colors = [
            '#32CD32', // Verde (exitoso, pagado, activo, completado)
            '#FFD700', // Amarillo (pendiente, en espera, procesando)
            '#FF6347', // Rojo (cancelado, falta información)
            '#FF4500', // Naranja (activo, recurrente, destacado)
            '#00BFFF', // Azul (nuevo, enviado, proveedor)
            '#A9A9A9', // Gris (inactivo)
            '#800080', // Morado (cliente premium, recurrente)
            '#FFA500', // Naranja (en proceso, pendiente)
            '#DC143C', // Rojo oscuro (pedido cancelado, en espera)
        ];

        return [
            'name_tag' => $this->faker->randomElement($names), // Selección aleatoria de nombre basado en el estado del cliente
            'description' => $this->faker->randomElement($descriptions), // Selección aleatoria de descripción
            'color' => $this->faker->randomElement($colors), // Selección aleatoria de color
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
