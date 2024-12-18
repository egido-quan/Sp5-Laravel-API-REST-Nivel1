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
        public function new_player_can_be_registered_by_admin_role() {

            $this->withoutExceptionHandling();
    
            $userAdmin = User::factory()->create();
            $userAdmin->assignRole(Role::findByName('admin', 'api'));
            $userAdminToken = $userAdmin->createToken('admin')->accessToken;
    
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
                'Authorization' => 'Bearer ' . $userAdminToken
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
    
            $userAdmin->delete();
            $user = User::find($player->user_id);
            $user->delete();
        }

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
        $userPlayer = User::where('id', $player->user_id)->first();

        $response = $this->get("api/player_info/{$player->ranking}", 
            ['Authorization' => 'Bearer ' . $userToken]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['info']['ranking'], $player->ranking);
        $this->assertEquals($responseJson['info']['name'], $userPlayer->name);
        $this->assertEquals($responseJson['info']['surname'], $userPlayer->surname);
        $this->assertEquals($responseJson['info']['height'], $player->height);
        $this->assertEquals($responseJson['info']['playing_hand'], $player->playing_hand);
        $this->assertEquals($responseJson['info']['backhand_style'], $player->backhand_style);
        $this->assertEquals($responseJson['info']['briefing'], $player->briefing);
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player info delivered');

        $user->delete();
        $userPlayer->delete();

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
     /** @test */
    public function existing_player_can_be_found_by_user_role() {

        $this->withoutExceptionHandling();

        $userClerk = User::factory()->create();
        $userClerk->assignRole(Role::findByName('user', 'api'));
        $userClerkToken = $userClerk->createToken('user')->accessToken;

        $player = Player::factory()->create();
        $user = User::where('id', $player->user_id)->first();

        $response = $this->get("/api/search_player/",
        [
            'name'          => $user->name,
            'email'         => $user->email,
            'playing_hand'  => $player->playing_hand,
            'briefing'      => $player->briefing,
        ],
        [
            'Authorization' => 'Bearer ' . $userClerkToken
        ]);

        $response->assertOk();    
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Player search successful');

        $userClerk->delete();
        $user->delete();
        $player->delete();

    }
}
