<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserEditTest extends TestCase
{
    use Withfaker;

   /** @test */
   public function user_data_can_be_edited_by_admin_role() {  

    $this->withoutExceptionHandling();

    $userAdmin = User::where('email', "admin@admin")->first();
    $userAdminToken = $userAdmin->createToken('admin')->accessToken;

    do {
        $fakeName = $this->faker->firstName;
    } while (strlen($fakeName <5));
    do {
        $fakeSurname = $this->faker->lastName;
    } while (strlen($fakeSurname <5));
    $fakeEmail = $this->faker->safeEmail;
    $role = (rand(1,2) == 1) ? 'admin' : 'user';

    $response = $this->post('/api/register_user',
    [
        'name' => $fakeName,
        'surname' => $fakeSurname,
        'email' => $fakeEmail,
        'password' => bcrypt('xxx'),
        'role' => $role
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
    $newRole = ($role == 'admin') ? 'user' : 'admin';

    $user = User::latest('id')->first();
    $userId = $user->id;
    $response = $this->put("/api/edit_user/{$userId}",
    [
        'name' => $newFakeName,
        'surname' => $newFakeSurname,
        'email' => $newFakeEmail,
        'password' => bcrypt($newPassword),
        'role' => $newRole
    ],
    [
        'Authorization' => 'Bearer ' . $userAdminToken
    ]);

    $response->assertOk();

    //$editedUser = User::latest('id')->first();
    $editedUser = User::find($userId);
    $responseJson = $response->json();
    $this->assertEquals($responseJson['response_code'], '200');
    $this->assertEquals($responseJson['message'], 'User data modification successful');

    $this->assertEquals($editedUser->name, $newFakeName);
    $this->assertEquals($editedUser->surname, $newFakeSurname);
    $this->assertEquals($editedUser->email, $newFakeEmail);

    $response = $this->post('/api/delete_user',
    [
        'email' => $newFakeEmail,
    ]);

    }
    
   /** @test */
    public function user_partial_data_can_be_edited_by_admin_role() {  

        $this->withoutExceptionHandling();
    
        $userAdmin = User::where('email', "admin@admin")->first();
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;
    
        do {
            $fakeName = $this->faker->firstName;
        } while (strlen($fakeName <5));
        do {
            $fakeSurname = $this->faker->lastName;
        } while (strlen($fakeSurname <5));
        $fakeEmail = $this->faker->safeEmail;
        $role = (rand(1,2) == 1) ? 'admin' : 'user';
    
        $response = $this->post('/api/register_user',
        [
            'name' => $fakeName,
            'surname' => $fakeSurname,
            'email' => $fakeEmail,
            'password' => bcrypt('xxx'),
            'role' => $role
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);
    
        //$response->assertOk();
    
        /*do {
            $newFakeName = $this->faker->firstName;
        } while (strlen($newFakeName <5));*/
        do {
            $newFakeSurname = $this->faker->lastName;
        } while (strlen($newFakeSurname <5));
        $newFakeEmail = $this->faker->safeEmail;
        //$newPassword = 'yyy';
        //$newRole = ($role == 'admin') ? 'user' : 'admin';
    
        $user = User::latest('id')->first();
        $userId = $user->id;
        $response = $this->put("/api/edit_user/{$userId}",
        [
            //'name' => $newFakeName,
            'surname' => $newFakeSurname,
            'email' => $newFakeEmail,
            //'password' => bcrypt($newPassword),
            //'role' => $newRole
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);
    
        $response->assertOk();
    
        //$editedUser = User::latest('id')->first();
        $editedUser = User::find($userId);
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'User data modification successful');
    
        $this->assertEquals($editedUser->name, $fakeName);
        $this->assertEquals($editedUser->surname, $newFakeSurname);
        $this->assertEquals($editedUser->email, $newFakeEmail);
    
        $response = $this->post('/api/delete_user',
        [
            'email' => $newFakeEmail,
        ]);
    
        }
}
