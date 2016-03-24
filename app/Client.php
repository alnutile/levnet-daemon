<?php


namespace App;


use App\Interfaces\APIClientInterface;

class Client extends \GuzzleHttp\Client implements APIClientInterface
{

    
    public function sendUpdate($payload)
    {

        $payload = [
            'json' => $payload
        ];

        $results = $this->request('POST', 'results', $payload);

    }
    
}