<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;

class PlayerTest extends TestCase
{

     /** @test */
     public function can_get_To5Player_list() {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));
        $userToken = $user->createToken('user')->accessToken;
        $response = $this->get('api/top5_players', 
            ['Authorization' => 'Bearer ' . $userToken]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertCount(5,$responseJson['list']);
        $this->assertEquals($responseJson['list'][0][0], 1);
        $this->assertEquals($responseJson['list'][4][0], 5);
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'List delivered');

        $user->delete();

    }

     /** @test */
     public function can_get_player_info_with_ranking_2() {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));
        $userToken = $user->createToken('user')->accessToken;
        $response = $this->get('api/player_info/2', 
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

        $user->delete();

    }
}
