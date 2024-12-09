<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlayerController extends Controller
{
    public function top5Players () {

        $players = Player::orderBy('ranking')
            ->with('user')
            ->take(5)
            ->get();
            $players_slim = [];

        foreach ($players as $player) {
            $ranking = $player['ranking'];
            $name = $player['user']['name'];
            $surname = $player['user']['surname'];
            $players_slim [] = [$ranking, $name, $surname];

        }
        $response = [];
        $response['list'] = $players_slim;
        $response['response_code'] = '200';
        $response['message']       = 'List delivered';
        return response()->json($response);

   }

  
   public function playerInfo (Request $request) {

    try {
        $request->validate([
            'ranking'    => 'required|int',
        ]);
    }catch (ValidationException $e) {
        return response()->json([
            'response_code' => 422,
            'status'        => 'error',
            'message'       => 'Validation failed',
            'errors'        => $e->errors()
        ], 422);
    }


    $player = Player::where('ranking', $request->ranking)
        ->with('user')
        ->first();

        $playerInfo = [
            'ranking' => $player['ranking'],
            'name' => $player['user']['name'],
            'surname' => $player['user']['surname'],
            'email' => $player['user']['email'],
            'height' => $player['height'],
            'playing_hand' => $player['playing_hand'],
            'backhand_style' => $player['backhand_style'],
            'briefing' => $player['briefing'],
        ];
    
    $response = [];
    $response['info'] = $playerInfo;
    $response['response_code'] = '200';
    $response['message']       = 'Player info delivered';
    return response()->json($response);

    }
}
