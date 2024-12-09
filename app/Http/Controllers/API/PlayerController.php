<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
