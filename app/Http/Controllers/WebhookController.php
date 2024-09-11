<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MediaFile;
use App\Models\Message;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    
    private $verifyToken = 'ProyectoW2024-';

 
    
    public function verifyWebhook(Request $request)
    {
        try
        {
            $query = $request->query();
            $mode = $query['hub_mode'] ?? null;
            $token = $query['hub_verify_token'] ?? null;
            $challenge = $query['hub_challenge'] ?? null;
            

        
            if ($mode && $token) {
                if ($mode === 'subscribe' && $token === $this->verifyToken) {
                    return response($challenge, 200)->header('Content-Type', 'text/plain');
                }
            }

            throw new Exception('Invalid request');

        }
        catch (Exception $e)
        {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function receiveMessage(Request $request)
{
    try
    {
        $bodyContent = $request->json()->all();
        Log::info($bodyContent);

        if(isset($bodyContent['entry'][0]['changes'][0]['value']['messages'][0]) && isset($bodyContent['entry'][0]['changes'][0]['value']['contacts'][0]))
        {
            // Extraer los datos del mensaje y del contacto
            $messageData = $bodyContent['entry'][0]['changes'][0]['value']['messages'][0];
            $contactData = $bodyContent['entry'][0]['changes'][0]['value']['contacts'][0];

            // Obtener información del remitente
            $message_wa_id = $contactData['wa_id'];
            $sender_name = $contactData['profile']['name'] ?? '';

            // Buscar o crear el cliente
            $customer = Customer::where('wa_id', $message_wa_id)->first();

            if (!$customer) {
                Log::info('Creating new customer...');
                $customer = Customer::create([
                    'name' => $sender_name,
                    'wa_id' => $message_wa_id
                ]);
            }

            // Variables comunes
            $message_id = $messageData['id'];
            $message_type = $messageData['type'];
            $message_direction = 'incoming';
            $message_status = 'received';
            $timestamp = Carbon::createFromTimestamp($messageData['timestamp']);
            $idCustomer = $customer->id;

            // Variables para cada tipo de mensaje
            $message_body = '';
            $media_url = null;
            $caption = null;
            $latitude = null;
            $longitude = null;
            $reaction_emoji = null;
            $reaction_message_id = null;
            $contact_name = null;
            $contact_phone_numbers = null;
            $contact_emails = null;
            $document_name = null;

            // Inicializamos un array para los datos multimedia (si los hay)
            $mediaFilesData = [];

            // Switch para manejar diferentes tipos de mensajes
            switch ($message_type) {
                case 'text':
                    $message_body = $messageData['text']['body'];
                    break;

                case 'image':
                    $media_url = $messageData['image']['id']; // Usamos el ID del archivo
                    $media_extension = $messageData['image']['mime_type']; // Tipo MIME
                    $caption = $messageData['image']['caption'] ?? null;
                
                    $mediaFilesData[] = [
                        'media_type' => 'image',
                        'media_url' => $media_url,
                        'media_extension' => $media_extension, // Almacenar el MIME type
                        'caption' => $caption,
                    ];
                    break;

                case 'video':
                    $media_url = $messageData['video']['id']; // Usamos el ID del archivo
                    $media_extension = $messageData['video']['mime_type']; // Tipo MIME
                    $caption = $messageData['video']['caption'] ?? null;
                
                    $mediaFilesData[] = [
                        'media_type' => 'video',
                        'media_url' => $media_url,
                        'media_extension' => $media_extension, // Almacenar el MIME type
                        'caption' => $caption,
                    ];
                    break;

                case 'audio':
                    $media_url = $messageData['audio']['id']; // Usamos el ID del archivo
                    $media_extension = $messageData['audio']['mime_type']; // Tipo MIME
                
                    $mediaFilesData[] = [
                        'media_type' => 'audio',
                        'media_url' => $media_url,
                        'media_extension' => $media_extension, // Almacenar el MIME type
                    ];
                    break;

                case 'document':
                    $media_url = $messageData['document']['id']; // Usamos el ID del archivo
                    $media_extension = $messageData['document']['mime_type']; // Tipo MIME
                    $document_name = $messageData['document']['filename'];
                
                    $mediaFilesData[] = [
                        'media_type' => 'document',
                        'media_url' => $media_url,
                        'media_extension' => $media_extension, // Almacenar el MIME type
                        'caption' => $document_name,
                    ];
                    break;

                case 'sticker':
                    $media_url = $messageData['sticker']['id']; // Usamos el ID del archivo
                    $media_extension = 'image/webp'; // Tipo MIME para stickers
                
                    $mediaFilesData[] = [
                        'media_type' => 'sticker',
                        'media_url' => $media_url,
                        'media_extension' => $media_extension, // Almacenar el MIME type
                    ];
                    break;

                case 'reaction':
                    $reaction_emoji = $messageData['reaction']['emoji'];
                    $reaction_message_id = $messageData['reaction']['message_id'];
                    $message_body = "Reaccionó con: $reaction_emoji";
                    break;

                case 'location':
                    $latitude = $messageData['location']['latitude'];
                    $longitude = $messageData['location']['longitude'];
                    $message_body = "Ubicación: Lat $latitude, Long $longitude";
                    break;

                case 'contacts': 
                    $contact = $messageData['contacts'][0];
                    $contact_name = $contact['name']['formatted_name'] ?? null;

                    $phones = collect($contact['phones'])->pluck('phone')->toArray();
                    $contact_phone_numbers = implode(', ', $phones);

                    $emails = collect($contact['emails'])->pluck('email')->toArray();
                    $contact_emails = implode(', ', $emails);

                    $message_body = "Nombre del contacto: $contact_name\nPhones: $contact_phone_numbers\nEmails: $contact_emails";
                    break;

                default:
                    $message_body = "Tipo de mensaje no reconocido";
                    break;
            }

            // Guardar el mensaje en la tabla 'messages'
            $message = Message::create([
                'customer_id' => $idCustomer,
                'message' => $message_body,
                'message_type' => $message_type,
                'direction' => $message_direction,
                'status' => $message_status,
                'whatsapp_message_id' => $message_id,
                'timestamp' => $timestamp,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'reaction_emoji' => $reaction_emoji,
                'reaction_message_id' => $reaction_message_id,
                'contact_name' => $contact_name,
                'contact_phone_numbers' => $contact_phone_numbers,
                'contact_emails' => $contact_emails,
                'document_name' => $document_name
            ]);

            // Si hay archivos multimedia, los guardamos en la tabla 'media_files'
            if (!empty($mediaFilesData)) {
                foreach ($mediaFilesData as $mediaData) {
                    MediaFile::create([
                        'message_id' => $message->id,
                        'media_type' => $mediaData['media_type'],
                        'media_url' => $mediaData['media_url'],
                        'media_extension' => $mediaData['media_extension'],
                        'caption' => $mediaData['caption'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
            ], 200);  
        }   
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Estructura de datos incorrecta.',
            ], 400);
        }

    }
    catch (Exception $e)
    {
        Log::error('Error al procesar el webhook:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}

    
}
