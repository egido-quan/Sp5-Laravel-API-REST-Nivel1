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
        $response = [];
        $response['list'] = $players;
        $response['response_code'] = '200';
        $response['message']       = 'List delivered';
        return response()->json($response);

   }
}
