<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
        use Withfaker;
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    */

    /** @test */
    public function new_user_can_be_registered_by_admin_role() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "admin@admin")->first();
        $userToken = $user->createToken('admin')->accessToken;

        $fakeName = $this->faker->firstName;
        $fakeSurname = $this->faker->lastName;
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
            'Authorization' => 'Bearer ' . $userToken
        ]);

        $response->assertOk();


        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Registration successful');

        $user = User::All()->last();
        $this->assertEquals($user->name, $fakeName);
        $this->assertEquals($user->surname, $fakeSurname);
        $this->assertEquals($user->email, $fakeEmail);
        $this->assertEquals($user->getRoleNames()->first(), $role);

    }

    /** @test */
    public function new_user_cannot_be_registered_by_user_role() {

        //$this->withoutExceptionHandling();

        $user = User::where('email', "jannik@sinner")->first();
        $userToken = $user->createToken('jannik')->accessToken;

        $fakeName = $this->faker->firstName;
        $fakeSurname = $this->faker->lastName;
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
            'Authorization' => 'Bearer ' . $userToken
        ]);

        $response->assertStatus(403);

    }

    /** @test */
    public function new_user_cannot_be_registered_if_validation_is_wrong() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "admin@admin")->first();
        $userToken = $user->createToken('admin')->accessToken;

        $fakeName = $this->faker->firstName;
        $fakeSurname = $this->faker->lastName;
        $fakeEmail = "wrong_email";
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
            'Authorization' => 'Bearer ' . $userToken
        ]);

        $response->assertStatus(422);

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '422');
        $this->assertEquals($responseJson['message'], 'Validation failed');

    }

    /** @test */
    public function user_can_be_deleted_by_admin_role() {

        //Create a new user
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
        $role = (rand(1,2) == 1) ? 'admin' : 'user';

        $this->post('/api/register_user',
        [
            'name' => $fakeName,
            'surname' => $fakeSurname,
            'email' => $fakeEmail,
            'password' => bcrypt('xxx'),
            'role' => $role
        ],
        [
            'Authorization' => 'Bearer ' . $userToken
        ]);

        //Delete the new user
        $response = $this->post('/api/delete_user',
        [
            'email' => $fakeEmail,
        ]);

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'User deleted');

    }

    /** @test */
    /*public function user_cannot_be_deleted_by_user_role() {

        $this->withoutExceptionHandling();

        //Create a new user
        $userAdmin = User::where('email', "admin@admin")->first();
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;

        $fakeName = $this->faker->firstName;
        $fakeSurname = $this->faker->lastName;
        $fakeEmail = $this->faker->safeEmail;
        $role = 'user';

        $this->post('/api/register_user',
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

        //$userAdmin->tokens()->update(['revoked' => true]);

        $this->post('/api/logout', 
            [], 
            ['Authorization' => 'Bearer ' . $userAdminToken]
        );

        //Try to delete the new user
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $user = User::where('email', $fakeEmail)->first();
        $userToken = $user->createToken('fake')->accessToken;

        $response = $this->post('/api/delete_user',
        ['email' => $fakeEmail],
        ['Authorization' => 'Bearer ' . $userToken]
    );
        $response->assertStatus(403);

    }*/
}
