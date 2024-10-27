<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/news', [NewsController::class, 'index']);


Route::get('/test-redis', function () {
    // Redis'e veri yazma
    Redis::set('test_key', 'Redis bağlantısı başarılı');

    // Redis'ten veri okuma
    $value = Redis::get('test_key');

    return response()->json(['message' => $value]);
});
