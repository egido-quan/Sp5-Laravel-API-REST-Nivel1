<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
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
    public function new_user_can_be_registered() {

        $this->withoutExceptionHandling();

        $response = $this->post('/api/register_user',
        [
            'name' => 'Bjorn',
            'surname' => 'Borg',
            'email' => 'bjorn@borg',
            'password' => 'xxx',
            'role' => 'admin'
        ]);

        $response->assertOk();


        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Registration successful');

        $user = User::All()->last();
        $this->assertEquals($user->name, 'Bjorn');
        $this->assertEquals($user->surname, 'Borg');
        $this->assertEquals($user->email, 'bjorn@borg');
        $this->assertEquals($user->getRoleNames()->first(), 'admin');

    }

    
}
