<?php

namespace App\Providers;

use App\Client;
use Illuminate\Support\ServiceProvider;

class APIClientProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Interfaces\APIClientInterface::class, function($app) {
            
            $config = [
                'base_uri'          => env('API_CLIENT_URL'),
                'timeout'           => 0,
                'allow_redirects'   => false,
                'verify'            => false,
                'headers'           => [
                    'Content-Type'  => 'application/json'
                ]
            ];
            
            $client = new Client($config);
            
            return $client;
        });
    }
}
