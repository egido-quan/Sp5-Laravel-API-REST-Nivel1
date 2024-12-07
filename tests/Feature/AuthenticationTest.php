<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
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
    public function login_is_working() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "jannik@sinner")->first();

        $response = $this->post('/api/login',
        [
            'email' => $user->email,
            'password' => 'xxx'
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
    }

    /** @test */
    public function logout_is_working() {

        $this->withoutExceptionHandling();

        $user = User::where('email', "andrei@rublev")->first();
        $userToken = $user->createToken('logout')->accessToken;
        $response = $this->post('/api/logout', 
            [], 
            ['Authorization' => 'Bearer ' . $userToken,]
        );

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Success logout');
    }
}
