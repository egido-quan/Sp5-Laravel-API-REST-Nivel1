<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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

  
   public function playerInfo (int $ranking) {

    $player = Player::where('ranking', $ranking)
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

    public function registerPlayer (Request $request) {

        try {
            $request->validate([
                'name'      => 'required|min:4',
                'surname'   => 'required|min:4',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|min:3',
                'height'    => 'required|integer|min:1',
                'playing_hand' => 'required|string|in:left,right,both',
                'backhand_style'  => 'required|string|in:"one hand","two hands"',
                'briefing' => 'required|string'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors()
            ], 422);
        }


        $player = new Player;

        if (!Player::exists()) {
            $ranking = 0;
        } else {
            $last_player = Player::orderBy('ranking', 'desc')->first();
            $ranking = $last_player->ranking;
        } 

        $user = new User;
        $user->assignRole('user');

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $player->ranking = $ranking + 1;
        $player->height = $request->height;
        $player->playing_hand = $request->playing_hand; 
        $player->backhand_style = $request->backhand_style;
        $player->briefing = $request->briefing;
        $player->user_id = $user->id;        

        $player->save();

        return response()->json([
            'response_code'  => '200',
            'status'         => 'success',
            'message'        => 'Player registration successful',
            'name'           => $user->name,
            'surname'        => $user->surname,
            'email'          => $user->email,
            'height'         => $player->height,
            'playing_hand'   => $player->playing_hand,
            'backhand_style' => $player->backhand_style,
            'briefing'       => $player->briefing,
            'user_id'        => $user->id,
            'player_id'      => $player->user_id,
        ]);

    }

    public function editPlayer (Request $request, $id) {

        try {
            $request->validate([
                'name'              => 'min:4',
                'surname'           => 'min:4',
                'email'             => 'string|email|max:255|unique:users',
                'password'          => 'min:3',
                'height'            => 'integer|min:1',
                'playing_hand'      => 'string|in:left,right,both',
                'backhand_style'    => 'string|in:"one hand","two hands"',
                'briefing'          => 'string'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors()
            ], 422);
        }


        $userToEdit = User::find($id);
        $userToEdit->name = ($request->name == "") ? $userToEdit->name : $request->name;
        $userToEdit->surname = ($request->surname == "") ? $userToEdit->surname : $request->surname;
        $userToEdit->email = ($request->email == "") ? $userToEdit->email : $request->email;
        $userToEdit->password = ($request->password == "") ? $userToEdit->password : Hash::make($request->password);

        $userToEdit->save();

        
        $playerToEdit = Player::where('user_id', $id)->first();
        
        //$playerToEdit->height           = $request->height;
        $playerToEdit->height = ($request->height == "") ? $playerToEdit->height : $request->height;
        //$playerToEdit->playing_hand     = $request->playing_hand;
        $playerToEdit->playing_hand = ($request->playing_hand == "") ? $playerToEdit->playing_hand : $request->playing_hand;
        //$playerToEdit->backhand_style   = $request->backhand_style;
        $playerToEdit->backhand_style = ($request->backhand_style == "") ? $playerToEdit->backhand_style : $request->backhand_style;
        //$playerToEdit->briefing         = $request->briefing;
        $playerToEdit->briefing = ($request->briefing == "") ? $playerToEdit->briefing : $request->briefing;

        $playerToEdit->save();

        $data = [];
        $data['response_code']  = '200';
        $data['status']         = 'success';
        $data['message']        = 'Player data modification successful';
        return response()->json($data);
    }

    


}
