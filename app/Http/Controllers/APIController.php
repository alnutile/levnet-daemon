<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class APIController
{

    public function store(Request $request)
    {
        $incoming = print_r($request->input(), 1);

        Log::info(sprintf("Request coming in %s", $incoming));

        $results = $request->input('results');

        if ($results && strpos($results, 'fail') > 0)
        {
            return response()->json([], 422);
        }

        File::put(storage_path('results.json'), json_encode($request->input(), JSON_PRETTY_PRINT));

        return response()->json([], 200);
    }
}