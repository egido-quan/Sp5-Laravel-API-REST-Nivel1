<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;

class PlayerEditTest extends TestCase
{
    use Withfaker;

   /** @test */
   public function player_data_can_be_edited_by_admin_role() {  

    $this->withoutExceptionHandling();

        $user = User::where('email', "admin@admin")->first();
        $userAdminToken = $user->createToken('admin')->accessToken;

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
            'briefing' => $fakeBriefing
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

    //$response->assertOk();

    do {
        $newFakeName = $this->faker->firstName;
    } while (strlen($newFakeName <5));
    do {
        $newFakeSurname = $this->faker->lastName;
    } while (strlen($newFakeSurname <5));
    $newFakeEmail = $this->faker->safeEmail;
    $newPassword = 'yyy';
    $newFakeHeight = rand(160, 200);
    $newFakeHand = (rand(1,2) == 1) ? 'left' : 'right';
    $newFakeBackhand = (rand(1,2) == 1) ? 'one hand' : 'two hands';
    $newFakeBriefing = $this->faker->sentence;

    $user = User::latest('id')->first();
    $userId = $user->id;
    $response = $this->put("/api/edit_player/{$userId}",
    [
        'name' => $newFakeName,
        'surname' => $newFakeSurname,
        'email' => $newFakeEmail,
        'password' => bcrypt($newPassword),
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

    //$editedUser = User::latest('id')->first();
    $editedUser = User::find($userId);
    $this->assertEquals($editedUser->name, $newFakeName);
    $this->assertEquals($editedUser->surname, $newFakeSurname);
    $this->assertEquals($editedUser->email, $newFakeEmail);

    //$editedPlayer = Player::latest('user_id')->first();
    $editedPlayer = Player::find($userId);
    $this->assertEquals($editedPlayer->height, $newFakeHeight);
    $this->assertEquals($editedPlayer->playing_hand, $newFakeHand);
    $this->assertEquals($editedPlayer->backhand_style, $newFakeBackhand);
    $this->assertEquals($editedPlayer->briefing, $newFakeBriefing);


    $this->post('/api/delete_user',
    [
        'email' => $newFakeEmail,
    ]);

    }

    /** @test */
   public function player_partial_data_can_be_edited_by_admin_role() {  

    $this->withoutExceptionHandling();

        $user = User::where('email', "admin@admin")->first();
        $userAdminToken = $user->createToken('admin')->accessToken;

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
            'briefing' => $fakeBriefing
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

    //$response->assertOk();

    do {
        $newFakeName = $this->faker->firstName;
    } while (strlen($newFakeName <5));

    $newFakeEmail = $this->faker->safeEmail;
    $newPassword = 'yyy';
    $newFakeHand = (rand(1,2) == 1) ? 'left' : 'right';
    $newFakeBriefing = $this->faker->sentence;

    $user = User::latest('id')->first();
    $userId = $user->id;
    $response = $this->put("/api/edit_player/{$userId}",
    [
        'name' => $newFakeName,
        'email' => $newFakeEmail,
        'password' => bcrypt($newPassword),
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

    //$editedUser = User::latest('id')->first();
    $editedUser = User::find($userId);
    $this->assertEquals($editedUser->name, $newFakeName);
    $this->assertEquals($editedUser->surname, $fakeSurname);
    $this->assertEquals($editedUser->email, $newFakeEmail);

    //$editedPlayer = Player::latest('user_id')->first();
    $editedPlayer = Player::find($userId);
    $this->assertEquals($editedPlayer->height, $fakeHeight);
    $this->assertEquals($editedPlayer->playing_hand, $newFakeHand);
    $this->assertEquals($editedPlayer->backhand_style, $fakeBackhand);
    $this->assertEquals($editedPlayer->briefing, $newFakeBriefing);


    $this->post('/api/delete_user',
    [
        'email' => $newFakeEmail,
    ]);

    }
}