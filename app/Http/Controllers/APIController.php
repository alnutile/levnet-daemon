<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APIController
{

    public function store(Request $request)
    {

        $incoming = print_r($request->input(), 1);
        
        Log::info(sprintf("Request coming in %s", $incoming));

        return "foo"; //Response::json(['message' => 'saved results'], 200);
    }
}