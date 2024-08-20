<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class WebhookController extends Controller
{
    
    private $verifyToken = 'proyectoW2024-';

    public function verifyWebhook(Request $request)
    {
        try
        {

            $query = $request->query();
            $mode = $query['hub_mode'];
            $token = $query['hub_verify_token'];
            $challenge = $query['hub_challenge'];
            

        
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

    public function processWebhook(Request $request)
    {
        try
        {
            $bodyContent = json_decode($request->getContent(),true);
            $body = '';

            $value = $bodyContent['entry'][0]['changes'][0]['value'];


            if (!empty($value['messages'])){
                if ($value['messages'][0]['type'] == 'text'){
                    $body = $value['messages'][0]['text']['body'];
                    $name = $value['contacts'][0]['profile']['name'];
                }

            }
            return response()->json([
                'success' => true,
                'data' =>  $body,
                'name' => $name,
            ], 200);  

        }
        catch (Exception $e)
        {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
