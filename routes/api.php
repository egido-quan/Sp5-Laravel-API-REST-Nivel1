<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\PlayerController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');*/

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:api');
Route::post('/register_user', [RegistrationController::class, 'register'])->middleware(['auth:api', 'role:admin']);
Route::post('/delete_user', [RegistrationController::class, 'delete'])->middleware(['auth:api', 'role:admin']);

Route::get('/top5_players', [PlayerController::class, 'top5Players'])->middleware('auth:api');
