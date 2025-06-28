<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message"=>"backend Laravel ".date("Y-m-d H:i:s")]);
});

Route::get('/endpoint1', function () {
    Log::info("cek123");
    Log::error("Andin cantik");
    return response()->json(["message"=>"Endpoint 1 ".date("Y-m-d H:i:s")." ".request()->input("param1")]);
});
