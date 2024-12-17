<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Challenge;
use App\Models\Player;

class ChallengeController extends Controller
{
    public function show($player_id) {

        $player = Player::find($player_id);
        $challengesList = $player
        ->allChallenges();

        return response()->json([
            'response_code'     => '200',
            'status'            => 'success',
            'message'           => 'Challenge list successful',
            'challenges'        => $challengesList

        ]);
    }
    
    public function registerChallenge (Request $request) {

       try {
            $request->validate([
                'player1_user_id' => 'required|exists:players,user_id|different:player2_user_id',
                'player2_user_id' => 'required|exists:players,user_id|different:player1_user_id',
                'score' => 'required|array|min:4',
                'score.*' => 'integer|min:0', 
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors()
            ], 422);
        }

        return self::addChallenge($request);

    }

    public function autoScore(Request $request) {

        try {
            $request->validate([
                'player1_user_id' => 'required|exists:players,user_id|different:player2_user_id',
                'player2_user_id' => 'required|exists:players,user_id|different:player1_user_id',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors()
            ], 422);
        }

        $score = self::createScore();
        $request->merge([
            'score' => $score
            ]);

        return self::addChallenge($request);

    }

    protected function addChallenge($request) {

        $player1 = Player::find($request->player1_user_id);
        $player2 = Player::find($request->player2_user_id);

        $message = self::scoreValidation($request);

        if ($message != "OK") {
            return  response()->json([
                'response_code' => 412,
                'status'        => 'error',
                'message'       => $message
            ]);
        }        

        $challenge = new Challenge;

        $challenge->player1_user_id = $player1->user_id;
        $challenge->player2_user_id = $player2->user_id;
        $challenge->score = json_encode($request->score);      

        $challenge->save();

        $winner = self::ganador($request->score);
        $player1_ranking = $player1->ranking;
        $player2_ranking = $player2->ranking;
        if (($winner == 1 && ($player1->ranking > $player2->ranking))
            || ($winner == 2 && ($player1->ranking < $player2->ranking))) {
            $player1->ranking = $player2_ranking;
            $player1->save();
            $player2->ranking = $player1_ranking;
            $player2->save();
         }

        return response()->json([
            'response_code'         => '200',
            'status'                => 'success',
            'message'               => 'Challenge registration successful',
            'player1_user_id'       => $challenge->player1_user_id,
            'player1_old_ranking'   => $player1_ranking,
            'player1_new_ranking'   => $player1->ranking,
            'player2_user_id'       => $challenge->player2_user_id,
            'player2_old_ranking'   => $player2_ranking,
            'player2_new_ranking'   => $player2->ranking,
            'score'                 => $challenge->score,

        ]);


    }

    public function delete(int $id)
    {

        $challenge = Challenge::find($id);

        if ($challenge ) {               

            $data = [];
            $data['response_code']      = '200';
            $data['status']             = 'success';
            $data['message']            = 'Challenge deleted';
            $data['challenge_id']       = $challenge->id;

            $challenge->delete();
            return response()->json($data);

        } else {
            $data = [];
            $data['response_code']  = '404';
            $data['status']         = 'error';
            $data['message']        = 'Not existing challenge';
            return response()->json($data);            
        }
    }

    protected function scoreValidation($request):string {

        $player1 = Player::find($request->player1_user_id);
        $player2 = Player::find($request->player2_user_id);

        if (self::isSamePlayer($player1->ranking, $player2->ranking))
            return "A player cannot challenge herself/himself";

        elseif (!self::isRankgingGapOk($player1->ranking, $player2->ranking))
            return "Ranking gap between players must be 3 or lower";

        elseif (!self::isSetScoreOK($request->score['player1_set1'], $request->score['player2_set1']))
            return "1st set score is wrong";

        elseif (!self::isSetScoreOK($request->score['player1_set2'], $request->score['player2_set2']))
            return "2nd set score is wrong";
    
        elseif (isset($request->score['player1_set3']) || isset($request->score['player2_set3'])) {
            if (!self::isSetScoreOK($request->score['player1_set3'], $request->score['player2_set3']))
            return "3rd set score is wrong";
        } 

        return "OK";
    }

    protected function isSamePlayer($p1_ranking, $p2_ranking) {
        if ($p1_ranking === $p2_ranking) {
            return true;
        } else {
            return false;
        }
    }

    protected function isRankgingGapOk($p1_ranking, $p2_ranking) {
        if (abs($p1_ranking - $p2_ranking) > 3) {
            return false;
        } else {
            return true;
        }
    }

    protected function isSetScoreOK($p1_set_score, $p2_set_score) {
        if (($p1_set_score == 7 && $p2_set_score == 7) || 
            (($p1_set_score == 7 || $p2_set_score == 7) && abs($p1_set_score - $p2_set_score) > 2) ||
            (($p1_set_score < 7 && $p2_set_score < 7) && abs($p1_set_score - $p2_set_score) < 2) ||
            (($p1_set_score < 6 && $p2_set_score < 6))) {
            return false;
        } else {
            return true;
        }
    }

    protected function ganador($score) {
        $jug1 = 0;
        $jug2 = 0;
        for ($i=1; $i<=count($score)/2; $i += 1) {
            $diff = $score["player1_set{$i}"] - $score["player2_set{$i}"];
            if ($diff > 0) {
                $jug1 ++;
            } else {
                $jug2 ++;
            }
        }
        return ($jug1>$jug2) ? 1 : 2;
    }

    protected function createScore() {

        $p1_set1 = rand(0,7);
        $p2_set1 = self::createSet($p1_set1);
        $p1_set2 = rand(0,7);
        $p2_set2 = self::createSet($p1_set2);
        if (($p1_set1 - $p2_set1) * ($p1_set2 - $p2_set2) < 0) {
            $p1_set3 = rand(0,7);
            $p2_set3 = self::createSet($p1_set3);
            return $score = [
                "player1_set1" => $p1_set1,
                "player2_set1" => $p2_set1,
                "player1_set2" => $p1_set2,
                "player2_set2" => $p2_set2,
                "player1_set3" => $p1_set3,
                "player2_set3" => $p2_set3
            ]; } else {        
            return $score = [
                "player1_set1" => $p1_set1,
                "player2_set1" => $p2_set1,
                "player1_set2" => $p1_set2,
                "player2_set2" => $p2_set2,
            ];     
        }

    }

    protected function createSet(int $p1_set) {
        return match(true) {
            $p1_set == 7 => rand(5,6),
            $p1_set == 6 => rand(0,4),
            $p1_set == 5 => 7,
            $p1_set < 5 => 6
        };
        
    }
    
    
}
