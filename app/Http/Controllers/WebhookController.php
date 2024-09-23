<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MediaFile;
use App\Models\Message;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    //public function receiveMessage(Request $request)
    //{
    //    try
    //    {
    //        $bodyContent = $request->json()->all();
    //        Log::info($bodyContent);
//
    //        if(isset($bodyContent['entry'][0]['changes'][0]['value']['messages'][0]) && isset($bodyContent['entry'][0]['changes'][0]['value']['contacts'][0]))
    //        {
    //            // Extraer los datos del mensaje y del contacto
    //            $messageData = $bodyContent['entry'][0]['changes'][0]['value']['messages'][0];
    //            $contactData = $bodyContent['entry'][0]['changes'][0]['value']['contacts'][0];
//
    //            // Obtener información del remitente
    //            $message_wa_id = $contactData['wa_id'];
    //            $sender_name = $contactData['profile']['name'] ?? '';
//
    //            $customer = Customer::where('wa_id', $message_wa_id)->first();
    //            if (!$customer) {
    //                Log::info('Creating new customer...');
    //                $customer = Customer::create([
    //                    'name' => $sender_name,
    //                    'wa_id' => $message_wa_id
    //                ]);
    //            }
//
    //            // Variables comunes
    //            $message_id = $messageData['id'];
    //            $message_type = $messageData['type'];
    //            $message_direction = 'incoming';
    //            $message_status = 'received';
    //            $timestamp = Carbon::createFromTimestamp($messageData['timestamp']);
    //            $idCustomer = $customer->id;
//
    //            // Variables para cada tipo de mensaje
    //            $message_body = '';
    //            $media_url = null;
    //            $media_extension = null;
    //            $caption = null;
    //            $latitude = null;
    //            $longitude = null;
    //            $reaction_emoji = null;
    //            $reaction_message_id = null;
    //            $contact_name = null;
    //            $contact_phone_numbers = null;
    //            $contact_emails = null;
    //            $document_name = null;
//
    //            // Inicializamos un array para los datos multimedia (si los hay)
    //            $mediaFilesData = [];
//
    //            // Switch para manejar diferentes tipos de mensajes
    //            switch ($message_type) {
    //                case 'text':
    //                    $message_body = $messageData['text']['body'];
    //                    break;
//
    //                case 'image':
    //                    $media_url = $messageData['image']['id']; 
    //                    $media_extension = $messageData['image']['mime_type']; 
    //                    $caption = $messageData['image']['caption'] ?? null;
    //                
//
    //                    break;
//
    //                case 'video':
    //                    $media_url = $messageData['video']['id']; 
    //                    $media_extension = $messageData['video']['mime_type']; 
    //                    $caption = $messageData['video']['caption'] ?? null;
    //                
    //                    $mediaFilesData[] = [
    //                        'media_type' => 'video',
    //                        'media_url' => $media_url,
    //                        'media_extension' => $media_extension, 
    //                        'caption' => $caption,
    //                    ];
    //                    break;
//
    //                case 'audio':
    //                    $media_url = $messageData['audio']['id']; 
    //                    $media_extension = $messageData['audio']['mime_type']; 
    //                
    //                    $mediaFilesData[] = [
    //                        'media_type' => 'audio',
    //                        'media_url' => $media_url,
    //                        'media_extension' => $media_extension, 
    //                    ];
    //                    break;
//
    //                case 'document':
    //                    $media_url = $messageData['document']['id']; 
    //                    $media_extension = $messageData['document']['mime_type']; 
    //                    $document_name = $messageData['document']['filename'];
    //                
    //                    $mediaFilesData[] = [
    //                        'media_type' => 'document',
    //                        'media_url' => $media_url,
    //                        'media_extension' => $media_extension, 
    //                        'caption' => $document_name,
    //                    ];
    //                    break;
//
    //                case 'sticker':
    //                    $media_url = $messageData['sticker']['id']; 
    //                    $media_extension = 'image/webp'; 
    //                
    //                    $mediaFilesData[] = [
    //                        'media_type' => 'sticker',
    //                        'media_url' => $media_url,
    //                        'media_extension' => $media_extension, 
    //                    ];
    //                    break;
//
    //                case 'reaction':
    //                    $reaction_emoji = $messageData['reaction']['emoji'];
    //                    $reaction_message_id = $messageData['reaction']['message_id'];
    //                    $message_body = "Reaccionó con: $reaction_emoji";
    //                    break;
//
    //                case 'location':
    //                    $latitude = $messageData['location']['latitude'];
    //                    $longitude = $messageData['location']['longitude'];
    //                    $message_body = "Ubicación: Lat $latitude, Long $longitude";
    //                    break;
//
    //                case 'contacts': 
    //                    $contact = $messageData['contacts'][0];
    //                    $contact_name = $contact['name']['formatted_name'] ?? null;
//
    //                    $phones = collect($contact['phones'])->pluck('phone')->toArray();
    //                    $contact_phone_numbers = implode(', ', $phones);
//
    //                    $emails = collect($contact['emails'])->pluck('email')->toArray();
    //                    $contact_emails = implode(', ', $emails);
//
    //                    $message_body = "Nombre del contacto: $contact_name\nPhones: $contact_phone_numbers\nEmails: $contact_emails";
    //                    break;
//
    //                default:
    //                    $message_body = "Tipo de mensaje no reconocido";
    //                    break;
    //            }
//
    //            // Guardar el mensaje en la tabla 'messages'
    //            $message = Message::create([
    //                'customer_id' => $idCustomer,
    //                'message' => $message_body,
    //                'message_type' => $message_type,
    //                'direction' => $message_direction,
    //                'status' => $message_status,
    //                'whatsapp_message_id' => $message_id,
    //                'timestamp' => $timestamp,
    //                'latitude' => $latitude,
    //                'longitude' => $longitude,
    //                'reaction_emoji' => $reaction_emoji,
    //                'reaction_message_id' => $reaction_message_id,
    //                'contact_name' => $contact_name,
    //                'contact_phone_numbers' => $contact_phone_numbers,
    //                'contact_emails' => $contact_emails,
    //                'document_name' => $document_name
    //            ]);
//
    //            // Si hay archivos multimedia, los guardamos en la tabla 'media_files'
    //            if (!empty($mediaFilesData)) {
    //                foreach ($mediaFilesData as $mediaData) {
    //                    MediaFile::create([
    //                        'message_id' => $message->id,
    //                        'media_type' => $mediaData['media_type'],
    //                        'media_url' => $mediaData['media_url'],
    //                        'media_extension' => $mediaData['media_extension'],
    //                        'caption' => $mediaData['caption'] ?? null,
    //                    ]);
    //                }
    //            }
//
    //            return response()->json(['success' => true], 200);  
    //        }   
    //        else
    //        {
    //            return response()->json(['success' => false, 'message' => 'Estructura de datos incorrecta.'], 400);
    //        }
//
    //    }
    //    catch (Exception $e)
    //    {
    //        Log::error('Error al procesar el webhook:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
//
    //        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    //    }
    //}
//
//
    //// $mediaFilesData = $this->handleMediaMessage($messageData, $message_type);
    //// Método para manejar mensajes multimedia
    //public function handleMediaMessage($messageData, $mediaType)
    //{
    //    $mediaFilesData = [];
//
    //    // Obtener el ID del archivo multimedia
    //    $mediaId = $messageData[$mediaType]['id'];
//
    //    // Obtener el enlace de descarga del archivo multimedia usando la API de WhatsApp
    //    $mediaUrl = $this->getMediaUrlFromWhatsApp($mediaId);
//
    //    // Definir el nombre del archivo según el tipo
    //    $fileName = $mediaId . '.' . $this->getFileExtension($mediaType);
//
    //    // Guardar el archivo en su carpeta correspondiente
    //    $filePath = $this->saveMediaByType($mediaUrl, $mediaType, $fileName);
//
    //    // Obtener el tipo MIME del archivo
    //    $mimeType = $this->getMimeType($mediaType);
//
    //    // Estructurar los datos del archivo multimedia en el mismo formato que espera $mediaFilesData
    //    $mediaFilesData[] = [
    //        'media_type' => $mediaType,
    //        'media_url' => $filePath,  // Ruta donde se guarda el archivo
    //        'mime_type' => $mimeType,   // Tipo MIME del archivo
    //        'caption' => $messageData[$mediaType]['caption'] ?? null,  // Agregar un subtítulo si existe
    //    ];
//
    //    return $mediaFilesData;
    //}
//
    //// Método para obtener la extensión de archivo según el tipo
    //public function getFileExtension($mediaType)
    //{
    //    $extensions = [
    //        'image' => 'jpg',
    //        'video' => 'mp4',
    //        'audio' => 'mp3',
    //        'sticker' => 'webp',
    //        'document' => 'pdf', // Puedes ajustar según sea necesario
    //    ];
//
    //    return $extensions[$mediaType] ?? 'bin'; // 'bin' por defecto si no se reconoce el tipo
    //}
//
    //// Método para obtener el tipo MIME según el tipo de archivo
    //public function getMimeType($mediaType)
    //{
    //    $mimeTypes = [
    //        'image' => 'image/jpeg',
    //        'video' => 'video/mp4',
    //        'audio' => 'audio/ogg',
    //        'sticker' => 'image/webp',
    //        'document' => 'application/pdf',
    //    ];
//
    //    return $mimeTypes[$mediaType] ?? 'application/octet-stream';
    //}
//
    //// Método para obtener la URL del archivo multimedia desde la API de WhatsApp
    //public function getMediaUrlFromWhatsApp($mediaId)
    //{
    //    $whatsappApiUrl = "https://graph.facebook.com/v15.0/$mediaId";
//
    //    // Llama a la API de WhatsApp para obtener la URL del archivo multimedia
    //    $response = Http::withToken('your-whatsapp-api-token')->get($whatsappApiUrl);
//
    //    // Devuelve la URL del archivo multimedia
    //    return $response['url'];
    //}








    //funcion que podriamos usar
    
    public function receiveMessage(Request $request)
    {
        try
        {
            $bodyContent = $request->json()->all();
            Log::info($bodyContent);
    
            if (isset($bodyContent['entry'][0]['changes'][0]['value']['messages'][0]) && isset($bodyContent['entry'][0]['changes'][0]['value']['contacts'][0]))
            {
                // Extraer datos del mensaje y contacto
                $messageData = $bodyContent['entry'][0]['changes'][0]['value']['messages'][0];
                $contactData = $bodyContent['entry'][0]['changes'][0]['value']['contacts'][0];
    
                // Información del remitente
                $message_wa_id = $contactData['wa_id'];
                $sender_name = $contactData['profile']['name'] ?? '';
    
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
    
                // Variables para almacenar información del mensaje
                $message_body = '';
                $mediaFilesData = [];
    
                // Manejar diferentes tipos de mensajes
                switch ($message_type) {
                    case 'text':
                        $message_body = $messageData['text']['body'];
                        break;
    
                    case 'image':
                    case 'video':
                    case 'audio':
                    case 'document':
                    case 'sticker':
                        $this->handleMediaMessage($messageData, $message_type, $mediaFilesData);
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
                        $this->handleContactMessage($messageData, $message_body);
                        break;
    
                    default:
                        $message_body = "Tipo de mensaje no reconocido";
                        break;
                }
    
                // Guardar el mensaje en la base de datos
                $message = Message::create([
                    'customer_id' => $idCustomer,
                    'message' => $message_body,
                    'message_type' => $message_type,
                    'direction' => $message_direction,
                    'status' => $message_status,
                    'whatsapp_message_id' => $message_id,
                    'timestamp' => $timestamp,
                ]);
    
                // Guardar archivos multimedia si los hay
                if (!empty($mediaFilesData)) {
                    foreach ($mediaFilesData as $mediaData) {
                        // Verifica que 'media_url' y 'media_extension' no estén vacíos
                        if (empty($mediaData['media_url']) || empty($mediaData['media_extension'])) {
                            Log::error('Media URL or extension is empty.', ['mediaData' => $mediaData]);
                            continue;  // Salta este archivo si falta información
                        }
                
                        // Guarda el archivo en el disco
                        $extension_formated = explode('/', $mediaData['media_extension']);
                        $extension_formated = end($extension_formated);

                        $fileName = $mediaData['media_url'] . '.' . $extension_formated;
                        $filePath = $this->saveMediaToDisk($mediaData['media_url'], $mediaData['media_type'], $fileName);
                
                        if ($filePath) {
                            // Guarda en la base de datos
                            MediaFile::create([
                                'message_id' => $message->id,
                                'media_type' => $mediaData['media_type'],
                                'media_url' => $fileName,  // Usa el nombre del archivo, no la ruta completa
                                'media_extension' => $extension_formated,
                                'caption' => $mediaData['caption'] ?? null,
                            ]);
                        } else {
                            Log::error('Fallo al guardar el archivo.', ['mediaData' => $mediaData]);
                        }
                    }
                }
                
    
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Estructura de datos incorrecta.'], 400);
            }
        }
        catch (Exception $e)
        {
            Log::error('Error al procesar el webhook:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    
    // Maneja los mensajes de tipo multimedia
    private function handleMediaMessage($messageData, $mediaType, &$mediaFilesData)
    {
        $media_url = $messageData[$mediaType]['id']; 
        $media_extension = explode('/',$messageData[$mediaType]['mime_type']);
        $media_extension = end($media_extension);
        $caption = $messageData[$mediaType]['caption'] ?? null;
    
        $mediaFilesData[] = [
            'media_type' => $mediaType,
            'media_url' => $media_url,
            'media_extension' => $media_extension, 
            'caption' => $caption,
        ];

        return $mediaFilesData;
    }
    
    // Maneja los mensajes de tipo contacto
    private function handleContactMessage($messageData, &$message_body)
    {
        $contact = $messageData['contacts'][0];
        $contact_name = $contact['name']['formatted_name'] ?? null;
    
        $phones = collect($contact['phones'])->pluck('phone')->toArray();
        $contact_phone_numbers = implode(', ', $phones);
    
        $emails = collect($contact['emails'])->pluck('email')->toArray();
        $contact_emails = implode(', ', $emails);
    
        $message_body = "Nombre del contacto: $contact_name\nPhones: $contact_phone_numbers\nEmails: $contact_emails";
    }
    
    public function saveMediaToDisk($mediaUrl, $mediaType, $fileName)
    {
        $disk = match($mediaType) {
            'image' => 'media_image',
            'video' => 'media_video',
            'audio' => 'media_audio',
            'sticker' => 'media_sticker',
            'document' => 'media_document',
            default => 'media_extra',
        };
    
        if (empty($fileName)) {
            Log::error('Nombre de archivo incompatible.');
            return null;
        }
    
        try {
            // Obtiene el contenido del archivo desde la URL
            $fileContent = Http::get($this->getMediaUrlFromWhatsApp($mediaUrl))->body();

            dd($fileContent);
            
            // Guarda el contenido del archivo en el disco
            Storage::disk($disk)->put($fileName, $fileContent);
        
            // Construye la ruta completa manualmente
            $filePath = storage_path('app/private' . $disk . '/' . $fileName);
            
            Log::info("Archivo guardado. Carpeta: $disk, Nombre del archivo: $fileName, Ruta: $filePath");
            return $fileName;  // Retorna el nombre del archivo, no la ruta completa
        } catch (\Exception $e) {
            Log::error('Error al guardar el archivo:', ['error' => $e->getMessage()]);
            return null;
        }
    }

    
    private function getMediaUrlFromWhatsApp($mediaId)
    {
        $whatsappUrl = env('WHATSAPP_API_URL');
        $whatsapp_version = env('WHATSAPP_API_VERSION');
        $whatsapp_token = env('WHATSAPP_ACCESS_TOKEN');
        $whatsappUrlFull = $whatsappUrl . $whatsapp_version . $mediaId;
        

        try {
            $response = Http::withToken($whatsapp_token)->get($whatsappUrlFull);

            if ($response->successful()) {


                return $response->json('url'); // Asegúrate de que esta clave exista en la respuesta

            } else {
                Log::error('Error fetching media URL from WhatsApp', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching media URL from WhatsApp:', ['error' => $e->getMessage()]);
        }
    
        return '';
    }
    
    
    
}
