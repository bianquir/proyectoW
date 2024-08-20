<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage()
    {

        $apiKey = config('services.whatsapp.api_key');


        $message = [
            'messaging_product' => "whatsapp",
            "to" => "54344615581705",
            "type" => "template",
            "template" => [
                            "name" => 'hello_world',
                            "language" => [
                                "code"=> "en_US"
                                ]
                            ]
        ];


        $messageJson = json_encode($message);

        echo $messageJson;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v20.0/371857952678909/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $messageJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}



$token = env('WHATSAPP_API_TOKEN');
$message = [
    'messaging_product' => "whatsapp",
    "to" => "54344615581705",
    "type" => "template",
    "template" => [
                    "name" => 'hello_world',
                    "language" => [
                        "code"=> "en_US"
                        ]
                    ]
];