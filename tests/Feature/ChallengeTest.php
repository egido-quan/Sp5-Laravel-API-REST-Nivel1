<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Player;

class ChallengeTest extends TestCase
{
    /**
     * A basic feature test example.
     */

/** @test */
   public function new_players_challenge_can_be_registered_by_admin() { 
   
    $this->withoutExceptionHandling();

    $userAdmin = User::factory()->create();
    $userAdmin->assignRole(Role::findByName('admin', 'api'));
    $userAdminToken = $userAdmin->createToken('admin')->accessToken;

    $player1 = $player = Player::factory()->create();
    $player2 = $player = Player::factory()->create();

    //$player1_user_id = 2;
    //$player2_user_id = 3;
    $score = ([
        'player1_set1' => 6,
        'player2_set1' => 3,
        'player1_set2' => 4,
        'player2_set2' => 6,
        'player1_set3' => 6,
        'player2_set3' => 2
    ]);

    $response = $this->post('/api/register_challenge',
    [
        'player1_user_id' => $player1->user_id,
        'player2_user_id' => $player2->user_id,
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

    $this->assertEquals($challengeInfo['player1_user_id'], $player1->user_id);
    $this->assertEquals($challengeInfo['player2_user_id'], $player2->user_id);
    $this->assertEquals($challengeInfo['score'], json_encode($score));

    $userAdmin->delete();
    $user1 = User::find($player1->user_id);
    $user1->delete();
    $user2 = User::find($player2->user_id);
    $user2->delete();
    $challenge->delete();

    }

    
    /** @test */
   public function ranking_4_5_are_swapped_if_challenge_is_won() { 
   
    $this->withoutExceptionHandling();

    $userAdmin = User::factory()->create();
    $userAdmin->assignRole(Role::findByName('admin', 'api'));
    $userAdminToken = $userAdmin->createToken('admin')->accessToken;

    $player1 = Player::where('ranking',5)->first();
    $player2 = Player::where('ranking',4)->first();

    $score = ([
        'player1_set1' => 6,
        'player2_set1' => 3,
        'player1_set2' => 4,
        'player2_set2' => 6,
        'player1_set3' => 6,
        'player2_set3' => 2
    ]);

    $response = $this->post('/api/register_challenge',
    [
        'player1_user_id' => $player1->user_id,
        'player2_user_id' => $player2->user_id,
        'score' => $score
    ],
    [
        'Authorization' => 'Bearer ' . $userAdminToken
    ]);

    $responseJson = $response->json();
    $this->assertEquals($responseJson['response_code'], '200');
    $this->assertEquals($responseJson['message'], 'Challenge registration successful');

    $challenge = Challenge::all()->last();

    $player1 = Player::where('user_id',$player1->user_id)->first();
    $player2 = Player::where('user_id',$player2->user_id)->first();

    $this->assertEquals(4, $player1->ranking);
    $this->assertEquals(5, $player2->ranking);

    $userAdmin->delete();
    $challenge->delete();

    }

     /** @test */
   public function can_show_player_id_3_matches() { 
   
    $this->withoutExceptionHandling();

    $userClerk = User::factory()->create();
    $userClerk->assignRole(Role::findByName('admin', 'api'));
    $userClerkToken = $userClerk->createToken('admin')->accessToken;

    //$player1 = Player::where('user_id',3)->first();

    $response = $this->get('/api/challenge/3',
    [
        'Authorization' => 'Bearer ' . $userClerkToken
    ]);


    $responseJson = $response->json();
    $this->assertEquals($responseJson['response_code'], '200');
    $this->assertEquals($responseJson['message'], 'Challenge list successful');

    $userClerk->delete();

    }

      /** @test */
      public function new_challenge_can_be_deleted_by_admin_role() {

        $this->withoutExceptionHandling();

        $userAdmin = User::factory()->create();
        $userAdmin->assignRole(Role::findByName('admin', 'api'));
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;

        $player1 = Player::factory()->create();
        $player2 = Player::factory()->create();
        $score = '{
            "player1_set1" : 6,
            "player2_set1" : 3,
            "player1_set2" : 6,
            "player2_set2" : 4
       }';

        $challenge = new Challenge();
        $challenge->player1_user_id = $player1->user_id;
        $challenge->player2_user_id = $player2->user_id;
        $challenge->score = json_encode($score);      

        $challenge->save();

        $response = $this->delete("/api/delete_challenge/{$challenge->id}",
        [
            'id' => $challenge->id
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Challenge deleted');

        $userAdmin->delete();
        $user1 = User::find($player1->user_id);
        $user1->delete();
        $user2 = User::find($player2->user_id);
        $user2->delete();

    }
      /** @test */
    public function new_players_automatic_challenge_can_be_registered_by_admin() { 
   
        $this->withoutExceptionHandling();
    
        $userAdmin = User::factory()->create();
        $userAdmin->assignRole(Role::findByName('admin', 'api'));
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;
    
        $player1 = $player = Player::factory()->create();
        $player2 = $player = Player::factory()->create();
    
        $response = $this->post('/api/auto_score',
        [
            'player1_user_id' => $player1->user_id,
            'player2_user_id' => $player2->user_id,
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
            'score' => json_decode($challenge['score'], true),
    
        ];
    
        $this->assertEquals($challengeInfo['player1_user_id'], $player1->user_id);
        $this->assertEquals($challengeInfo['player2_user_id'], $player2->user_id);
        $this->assertArrayHasKey('player1_set1', $challengeInfo['score']);
        $this->assertArrayHasKey('player1_set2', $challengeInfo['score']);
    
        $userAdmin->delete();
        $user1 = User::find($player1->user_id);
        $user1->delete();
        $user2 = User::find($player2->user_id);
        $user2->delete();
        $challenge->delete();    
        }

}
