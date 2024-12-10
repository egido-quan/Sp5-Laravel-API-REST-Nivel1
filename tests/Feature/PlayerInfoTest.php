<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PlayerInfoTest extends TestCase
{

    /** @test */
    public function can_get_player_info_with_ranking_2() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "jannik@sinner")->first();
        $userToken = $user->createToken('user')->accessToken;
        $response = $this->post('api/player_info/2', 
            [],
            ['Authorization' => 'Bearer ' . $userToken]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['info']['ranking'], 2);
        $this->assertEquals($responseJson['info']['name'], 'Daniil');
        $this->assertEquals($responseJson['info']['surname'], 'Medvedev');
        $this->assertEquals($responseJson['info']['playing_hand'], 'right');
        $this->assertEquals($responseJson['info']['backhand_style'], 'two hands');
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player info delivered');

    }


}
