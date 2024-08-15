<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^[\d]+$/', // Debe ser un número de teléfono válido
            'template' => 'required|string', // Plantilla del mensaje
            'params' => 'nullable|array' // Parámetros opcionales para la plantilla
        ]);

        // Parámetros de la API de WhatsApp
        $apiUrl = 'https://api.whatsapp.com/v1/messages'; // Cambia esta URL según el servicio que uses
        $token = env('WHATSAPP_API_TOKEN'); // Token de autenticación

        // Crea el mensaje usando los parámetros
        $message = $this->createMessage($validated['template'], $validated['params']);

        // Envía la solicitud a la API de WhatsApp
        $response = Http::withToken($token)->post($apiUrl, [
            'phone' => $validated['phone_number'],
            'message' => $message,
        ]);

        // Verifica la respuesta
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Mensaje enviado con éxito.']);
        } else {
            return response()->json(['success' => false, 'error' => $response->body()], $response->status());
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
