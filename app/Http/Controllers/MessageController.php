<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v18.0/371857952678909/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "messaging_product": "whatsapp",
    "to": "54344615581705",
    "type": "template",
    "template": {
        "name": "hello_world",
        "language": {
            "code": "en_US"
        }
    }
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer EAALmPyW95NUBO9Tbu0XNArw9WexRkUs7xUGvYBhWXlYRRFaeVbaVdL8kOpvBfoVVxsijJ4Qm0rUosBU46auDoe5XZBLJMbwFFRYuE5IS1LCUB86S4jWc53edczHoa1p8aJd44sWe4U7quJbLPkMnoQNZB49QJL7RfEp8iZCT736gZCP3vNRli11XYhbJamUZAdKtkfFZCrtstL0690QGsxvlkuTZCcZD'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
