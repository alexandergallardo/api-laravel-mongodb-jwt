<?php

use App\Http\Controllers\Api\CandidatoController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/auth', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    // Route::post('/register', [AuthController::class, 'register']);
});

Route::controller(CandidatoController::class)->group(function(){
    Route::post('/lead', 'store');
    Route::get('/lead/{id}', 'searchById');
    Route::get('/leads', 'list');
});
