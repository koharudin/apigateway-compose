<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message"=>"backend Laravel"]);
});

Route::get('/endpoint1', function () {
    return response()->json(["message"=>"Endpoint 1 ".date("Y-m-d H:i:s")]);
});
