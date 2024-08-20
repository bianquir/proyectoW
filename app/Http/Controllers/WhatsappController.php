<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class WhatsAppController extends Controller
{
    public function sendMessage(Request $request)
{
    // Validar los datos de entrada
    $validated = $request->validate([
        'phone_number' => 'required|regex:/^\+?[1-9]\d{1,14}$/', // Validación mejorada para números de teléfono
        'template' => 'required|string', // Plantilla del mensaje
        'params' => 'nullable|array' // Parámetros opcionales para la plantilla
    ]);

    // Parámetros de la API de WhatsApp
    $apiUrl = 'https://graph.facebook.com/v20.0/371857952678909/messages'; // Cambia esta URL según el servicio que uses
    $token = env('WHATSAPP_API_TOKEN'); // Token de autenticación

    // Crea el mensaje usando los parámetros
    $message = $this->createMessage($validated['template'], $validated['params']);

    // Envía la solicitud a la API de WhatsApp
    try {
        $response = Http::withToken($token)->post($apiUrl, [
            'to' => $validated['phone_number'], // 'to' es comúnmente usado en lugar de 'phone'
            'type' => 'text', // Especifica el tipo de mensaje, puede variar según la API
            'text' => [
                'body' => $message,
            ],
        ]);

        // Verifica la respuesta
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Mensaje enviado con éxito.']);
        } else {
            // Manejo más detallado de errores
            $errorResponse = $response->json();
            return response()->json([
                'success' => false,
                'error' => $errorResponse['error']['message'] ?? 'Error desconocido',
                'status_code' => $response->status()
            ], $response->status());
        }
    } catch (\Exception $e) {
        // Captura cualquier excepción y devuelve un error
        return response()->json([
            'success' => false,
            'error' => 'Error en la solicitud: ' . $e->getMessage()
        ], 500);
    }
}

private function createMessage($template, $params = [])
{
    // Reemplaza los parámetros en la plantilla
    foreach ($params as $key => $value) {
        $template = str_replace("{{{$key}}}", $value, $template);
    }

    return $template;
}

}