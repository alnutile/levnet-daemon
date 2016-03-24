<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

$app->get('/', function () use ($app) {
    return $app->version();
});


/**
 * Mock routes for local testing
 */

$app->post('/api/v1/results', 'APIController@store');