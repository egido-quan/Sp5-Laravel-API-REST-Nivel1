<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class Top5PlayersTest extends TestCase
{

    /** @test */
    public function can_get_To5Player_list() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "jannik@sinner")->first();
        $userToken = $user->createToken('user')->accessToken;
        $response = $this->get('api/top5_players', 
            ['Authorization' => 'Bearer ' . $userToken]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertCount(5,$responseJson['list'], 5);
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'List delivered');

    }


}
