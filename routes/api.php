<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\API\ChallengeController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');*/

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:api');
Route::post('/register_user', [UserController::class, 'register'])->middleware(['auth:api', 'role:admin']);
Route::post('/delete_user', [UserController::class, 'delete'])->middleware(['auth:api', 'role:admin']);
Route::put('/edit_user/{id}', [UserController::class, 'editUser'])->middleware(['auth:api', 'role:admin']);

Route::get('/top5_players', [PlayerController::class, 'top5Players'])->middleware('auth:api');
Route::get('/player_info/{ranking}', [PlayerController::class, 'playerInfo'])->middleware('auth:api');
Route::post('/register_player', [PlayerController::class, 'registerPlayer'])->middleware(['auth:api', 'role:admin']);
Route::put('/edit_player/{id}', [PlayerController::class, 'editPlayer'])->middleware(['auth:api', 'role:admin']);

Route::post('/register_challenge', [ChallengeController::class, 'registerChallenge'])->middleware(['auth:api', 'role:admin']);
Route::get('/challenge/{id}', [ChallengeController::class, 'show'])->middleware(['auth:api', 'role:user']);