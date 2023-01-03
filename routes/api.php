<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware("auth:sanctum")->group(function () {
    Route::prefix('chat')->group(function () {
        Route::post('/', [ChatController::class, 'sendMessage']);
        Route::get('/{user}', [ChatController::class, 'getConversation']);
        Route::get('/', [ChatController::class, 'getAllConversations']);
    });
});

Route::post('login', [LoginController::class, 'login']);
