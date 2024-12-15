<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;


class PlayerTest extends TestCase
{
    use Withfaker;

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
     public function can_get_player_info_from_player_ranking() {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));
        $userToken = $user->createToken('user')->accessToken;
        $player = Player::factory()->create();
        $user = User::where('id', $player->user_id)->first();

        $response = $this->get("api/player_info/{$player->ranking}", 
            ['Authorization' => 'Bearer ' . $userToken]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['info']['ranking'], $player->ranking);
        $this->assertEquals($responseJson['info']['name'], $user->name);
        $this->assertEquals($responseJson['info']['surname'], $user->surname);
        $this->assertEquals($responseJson['info']['height'], $player->height);
        $this->assertEquals($responseJson['info']['playing_hand'], $player->playing_hand);
        $this->assertEquals($responseJson['info']['backhand_style'], $player->backhand_style);
        $this->assertEquals($responseJson['info']['briefing'], $player->briefing);
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player info delivered');

        $user->delete();

    }

     /** @test */
    public function player_data_can_be_edited_by_admin_role() {  

        $this->withoutExceptionHandling();

        $userAdmin = User::factory()->create();
        $userAdmin->assignRole(Role::findByName('admin', 'api'));
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;

        $player = Player::factory()->create();

        do {
            $newFakeName = $this->faker->firstName;
        } while (strlen($newFakeName <5));
        do {
            $newFakeSurname = $this->faker->lastName;
        } while (strlen($newFakeSurname <5));
        $newFakeEmail = $this->faker->safeEmail;
        $newFakePassword = (fake()->password());
        $newFakeHeight = rand(160, 200);
        $newFakeHand = (rand(1,2) == 1) ? 'left' : 'right';
        $newFakeBackhand = (rand(1,2) == 1) ? 'one hand' : 'two hands';
        $newFakeBriefing = $this->faker->sentence;

        $response = $this->put("/api/edit_player/{$player->user_id}",
        [
            'name' => $newFakeName,
            'surname' => $newFakeSurname,
            'email' => $newFakeEmail,
            'password' => Hash::make($newFakePassword),
            'height' => $newFakeHeight,
            'playing_hand' => $newFakeHand,
            'backhand_style' => $newFakeBackhand,
            'briefing' => $newFakeBriefing
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

        $response->assertOk();    
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player data modification successful');

        $editedUser = User::find($player->user_id);
        $this->assertEquals($editedUser->name, $newFakeName);
        $this->assertEquals($editedUser->surname, $newFakeSurname);
        $this->assertEquals($editedUser->email, $newFakeEmail);

        $editedPlayer = Player::find($player->user_id);
        $this->assertEquals($editedPlayer->height, $newFakeHeight);
        $this->assertEquals($editedPlayer->playing_hand, $newFakeHand);
        $this->assertEquals($editedPlayer->backhand_style, $newFakeBackhand);
        $this->assertEquals($editedPlayer->briefing, $newFakeBriefing);

        $userAdmin->delete();
        $user = User::where('id', $player->user_id)->first();
        $user->delete();

    }

     /** @test */
    
    public function player_partial_data_can_be_edited_by_admin_role() {  

        $this->withoutExceptionHandling();

        $userAdmin = User::factory()->create();
        $userAdmin->assignRole(Role::findByName('admin', 'api'));
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;

        $player = Player::factory()->create();

        do {
            $newFakeName = $this->faker->firstName;
        } while (strlen($newFakeName <5));
        $newFakeEmail = $this->faker->safeEmail;
        $newFakeHand = (rand(1,2) == 1) ? 'left' : 'right';
        $newFakeBriefing = $this->faker->sentence;

        $response = $this->put("/api/edit_player/{$player->user_id}",
        [
            'name' => $newFakeName,
            'email' => $newFakeEmail,
            'playing_hand' => $newFakeHand,
            'briefing' => $newFakeBriefing
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

        $response->assertOk();    
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player data modification successful');

        $editedUser = User::find($player->user_id);
        $this->assertEquals($editedUser->name, $newFakeName);
        $this->assertEquals($editedUser->email, $newFakeEmail);

        $editedPlayer = Player::find($player->user_id);
        $this->assertEquals($editedPlayer->playing_hand, $newFakeHand);
        $this->assertEquals($editedPlayer->briefing, $newFakeBriefing);

        $userAdmin->delete();
        $user = User::where('id', $player->user_id)->first();
        $user->delete();
    }
}
