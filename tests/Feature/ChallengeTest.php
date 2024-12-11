<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Challenge;

class ChallengeTest extends TestCase
{
    /**
     * A basic feature test example.
     */

/** @test */
   public function players_2_and_3_challenge_can_be_registered() { 
   
    $this->withoutExceptionHandling();

    $user = User::where('email', "admin@admin")->first();
    $userAdminToken = $user->createToken('admin')->accessToken;

    $player1_user_id = 2;
    $player2_user_id = 3;
    $score = [
        'player1_set1' => 6,
        'player2_set1' => 3,
        'player1_set2' => 4,
        'player2_set2' => 6,
        'player1_set3' => 6,
        'player2_set3' => 2
    ];

    $response = $this->post('/api/register_challenge',
    [
        'player1_user_id' => $player1_user_id,
        'player2_user_id' => $player2_user_id,
        'score' => $score
    ],
    [
        'Authorization' => 'Bearer ' . $userAdminToken
    ]);



    $responseJson = $response->json();
    $this->assertEquals($responseJson['response_code'], '200');
    $this->assertEquals($responseJson['message'], 'Challenge registration successful');

    $challenge = Challenge::all()->last();

    $challengeInfo = [
        'player1_user_id' => $challenge['player1_user_id'],
        'player2_user_id' => $challenge['player2_user_id'],
        'score' => $challenge['score'],

    ];

    $this->assertEquals($challengeInfo['player1_user_id'], $player1_user_id);
    $this->assertEquals($challengeInfo['player2_user_id'], $player2_user_id);
    $this->assertEquals($challengeInfo['score'], $score);




    }



}
