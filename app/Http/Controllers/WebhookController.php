<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Message;
use Illuminate\Http\Request;
use Exception;
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
            $body='';
            $name='';
            Log::info('Solicitud entrante de whatsapp: ',['bodyContent' => $bodyContent]);


            if(isset($bodyContent['value']['messages'][0]) && isset($bodyContent['value']['contacts'][0]))
            {
                $messageData = $bodyContent['value']['messages'][0];
                $contactData = $bodyContent['value']['contacts'][0];

                $message_id = $messageData['id'];
                $message_wa_id = $contactData['wa_id'];
                $sender_name = $contactData['profile']['name'] ?? null;
                $message_body = $messageData['text']['body'] ?? '';
                $message_type = $messageData['type'];
                $timestamp = isset($messageData['timestamp']) ? date('Y-m-d H:i:s', $messageData['timestamp']) : null;

                



            }

            

            $value = $bodyContent['entry'][0]['changes'][0]['value'] ?? null;


            if ($value && !empty($value['messages'])) {
                if ($value['messages'][0]['type'] == 'text'){
                    $body = $value['messages'][0]['text']['body'];
                    $name = $value['contacts'][0]['profile']['name'];

                    Log::info('Mensaje recibido:', ['name' => $name, 'body' => $body]);
                }
            }


///////////////////////Crear mensaje /////////////
///////////////////////////////////////////////






            return response()->json([
                'success' => true,
                'data' =>  $body,
                'name' => $name,
            ], 200);  

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


    public function crearmensaje(){

        $dni = 39255959;
        $cuil = 20392559591;
        $name = 'Franco';
        $phone = 5493446581705;
        $messageApi = 'Hola buenas noches';
        $messagetype = 'text';


        $customer = new Customer();

        $customer->name = $name;
        $customer->phone_number = $phone;
        $customer->save();

        $message = $customer->messages()->create([
            'message' => $messageApi,
            'message_type' => $messagetype
        ]);

        $customer->message_id = $message->id;
        $customer->save();

    }




}
