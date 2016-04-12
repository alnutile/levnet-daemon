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
        
        $token = env('API_TOKEN');

        $results = $this->request('POST', sprintf('results?api_token=%s',  $token), $payload);
        
        return $results;

    }
    
}