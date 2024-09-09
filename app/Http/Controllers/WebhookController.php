<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
    
                $messageData = $bodyContent['entry'][0]['changes'][0]['value']['messages'][0];
                $contactData = $bodyContent['entry'][0]['changes'][0]['value']['contacts'][0];
    
                $message_wa_id = $contactData['wa_id'];
                $sender_name = $contactData['profile']['name'] ?? '';
    
                $customer = Customer::where('wa_id', $message_wa_id)->first();
    
                if (!$customer)
                {
                    Log::info('Creating new customer...');
                    $customer = Customer::Create([
                        'name' => $sender_name,
                        'wa_id' => $message_wa_id
                    ]);
                }
    
                $message_id = $messageData['id'];
                $message_body = $messageData['text']['body'] ?? '';
                $message_type = $messageData['type'];
                $message_direction = 'incoming';
                $message_status = 'received';
                $timestamp = Carbon::createFromTimestamp($messageData['timestamp']);
    
                Message::Create([
                    'customer_id' => $customer->id,
                    'message' => $message_body,
                    'message_type' => $message_type,
                    'direction' => $message_direction,
                    'status' => $message_status,
                    'whatsapp_message_id' => $message_id,
                    'timestamp' => $timestamp,
                ]);
        
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
