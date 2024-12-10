<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;

class PlayerRegistrationTest extends TestCase
{

    use Withfaker;
    /** @test */
    public function new_player_can_be_registered_by_admin_role() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "admin@admin")->first();
        $userToken = $user->createToken('admin')->accessToken;

        do {
            $fakeName = $this->faker->firstName;
        } while (strlen($fakeName <5));
        do {
            $fakeSurname = $this->faker->lastName;
        } while (strlen($fakeSurname <5));
        $fakeEmail = $this->faker->safeEmail;
        $fakeHeight = rand(160, 200);
        $fakeHand = (rand(1,2) == 1) ? 'left' : 'right';
        $fakeBackhand = (rand(1,2) == 1) ? 'one hand' : 'two hands';
        $fakeBriefing = $this->faker->sentence;

        $response = $this->post('/api/register_player',
        [
            'name' => $fakeName,
            'surname' => $fakeSurname,
            'email' => $fakeEmail,
            'password' => bcrypt('xxx'),
            'height' => $fakeHeight,
            'playing_hand' => $fakeHand,
            'backhand_style' => $fakeBackhand,
            'briefing' => $fakeBriefing,

        ],
        [
            'Authorization' => 'Bearer ' . $userToken
        ]);

        $response->assertOk();


        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player registration successful');

        $player = Player::with('user')
        ->get()
        ->last();

        $playerInfo = [
            'name' => $player['user']['name'],
            'surname' => $player['user']['surname'],
            'email' => $player['user']['email'],
            'height' => $player['height'],
            'playing_hand' => $player['playing_hand'],
            'backhand_style' => $player['backhand_style'],
            'briefing' => $player['briefing'],
        ];

        $this->assertEquals($playerInfo['name'], $fakeName);
        $this->assertEquals($playerInfo['surname'], $fakeSurname);
        $this->assertEquals($playerInfo['email'], $fakeEmail);
        $this->assertEquals($playerInfo['height'], $fakeHeight);
        $this->assertEquals($playerInfo['playing_hand'], $fakeHand);
        $this->assertEquals($playerInfo['backhand_style'], $fakeBackhand);
        $this->assertEquals($playerInfo['briefing'], $fakeBriefing);


    }
}
