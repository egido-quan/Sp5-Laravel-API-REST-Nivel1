<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthenticationTest extends TestCase
{

    /** @test */
    public function user_can_login() {

        $this->withoutExceptionHandling();

        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);
        $user->assignRole(Role::findByName('user', 'api'));

        $response = $this->post('/api/login',
        [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Success Login');
        $this->assertArrayHasKey('token', $responseJson);
        $this->assertNotEmpty($responseJson['token']);

        $response = $this->post('/api/login',
        [
            'email' => 'wrong mail',
            'password' => 'xxx'
        ]);

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '401');
        $this->assertEquals($responseJson['message'], 'Unauthorized');

        $user->delete();
    }

    /** @test */
    public function user_can_logout() {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));
        $userToken = $user->createToken('user')->accessToken;

        $response = $this->post('/api/logout', 
            [], 
            ['Authorization' => 'Bearer ' . $userToken,]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Success logout');

        $user->delete();
    }
}
