<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserTest extends TestCase
{
    use Withfaker;

      /** @test */
      public function new_user_can_be_registered_by_admin_role() {

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

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'Registration successful');

        $user = User::All()->last();
        $this->assertEquals($user->name, $fakeName);
        $this->assertEquals($user->surname, $fakeSurname);
        $this->assertEquals($user->email, $fakeEmail);
        $this->assertEquals($user->getRoleNames()->first(), $role);

        $userAdmin->delete();
        $user->delete();

    }

   
    /** @test */
    public function new_user_cannot_be_registered_if_validation_is_wrong() {

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
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

        $response->assertStatus(422);

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '422');
        $this->assertEquals($responseJson['message'], 'Validation failed');

        $userAdmin->delete();


    }

    /** @test */
    public function user_can_be_deleted_by_admin_role() {

        $this->withoutExceptionHandling();

        $userAdmin = User::factory()->create();
        $userAdmin->assignRole(Role::findByName('admin', 'api'));
        $userAdminToken = $userAdmin->createToken('admin')->accessToken;

        $userToDelete = User::factory()->create();

        $response = $this->post('/api/delete_user',
        [
            'email' => $userToDelete->email
        ],
        [
            'Authorization' => 'Bearer ' . $userAdminToken
        ]);

        $response->assertOk();

        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'User deleted');

        $userAdmin->delete();

    }

    /** @test */
    public function user_data_can_be_edited_by_admin_role() {  
 
     $this->withoutExceptionHandling();
 
     $userAdmin = User::factory()->create();
     $userAdmin->assignRole(Role::findByName('admin', 'api'));
     $userAdminToken = $userAdmin->createToken('admin')->accessToken;
 
     $user = User::factory()->create();
 
     do {
         $newFakeName = $this->faker->firstName;
     } while (strlen($newFakeName <5));
     do {
         $newFakeSurname = $this->faker->lastName;
     } while (strlen($newFakeSurname <5));
     $newFakeEmail = $this->faker->safeEmail;
     $newFakePassword = (fake()->password());
     $newRole = ($user->role == 'admin') ? 'user' : 'admin';
 
     $response = $this->put("/api/edit_user/{$user->id}",
     [
         'name' => $newFakeName,
         'surname' => $newFakeSurname,
         'email' => $newFakeEmail,
         'password' => Hash::make($newFakePassword),
         'role' => $newRole
     ],
     [
         'Authorization' => 'Bearer ' . $userAdminToken
     ]);
 
     $response->assertOk();
 
     $editedUser = User::find($user->id);
     $responseJson = $response->json();
     $this->assertEquals($responseJson['response_code'], '200');
     $this->assertEquals($responseJson['message'], 'User data modification successful');
 
     $this->assertEquals($editedUser->name, $newFakeName);
     $this->assertEquals($editedUser->surname, $newFakeSurname);
     $this->assertEquals($editedUser->email, $newFakeEmail);
 
     $userAdmin->delete();
     $user->delete();
 
     }
     
    /** @test */
    
     public function user_partial_data_can_be_edited_by_admin_role() {  
 
         $this->withoutExceptionHandling();
 
         $userAdmin = User::factory()->create();
         $userAdmin->assignRole(Role::findByName('admin', 'api'));
         $userAdminToken = $userAdmin->createToken('admin')->accessToken;
     
         $user = User::factory()->create();
     
 
         do {
             $newFakeSurname = $this->faker->lastName;
         } while (strlen($newFakeSurname <5));
         $newFakeEmail = $this->faker->safeEmail;
         $newFakePassword = (fake()->password());
         $newRole = ($user->role == 'admin') ? 'user' : 'admin';
     
         $response = $this->put("/api/edit_user/{$user->id}",
         [
             'surname' => $newFakeSurname,
             'email' => $newFakeEmail,
          ],
         [
             'Authorization' => 'Bearer ' . $userAdminToken
         ]);
     
         $response->assertOk();
     
         $editedUser = User::find($user->id);
         $responseJson = $response->json();
         $this->assertEquals($responseJson['response_code'], '200');
         $this->assertEquals($responseJson['message'], 'User data modification successful');
     
         $this->assertEquals($editedUser->name, $user->name);
         $this->assertEquals($editedUser->surname, $newFakeSurname);
         $this->assertEquals($editedUser->email, $newFakeEmail);
     
         $userAdmin->delete();
         $user->delete();
        }

           /** @test */
    public function existing_user_can_be_found_by_user_role() {

        $this->withoutExceptionHandling();

        $userClerk = User::factory()->create();
        $userClerk->assignRole(Role::findByName('user', 'api'));
        $userClerkToken = $userClerk->createToken('user')->accessToken;

        $user = User::factory()->create();

        $response = $this->post("/api/search_user/",
        [
            'email'         => $user->email,
        ],
        [
            'Authorization' => 'Bearer ' . $userClerkToken
        ]);

        $foundUser = User::where('email', $user->email)->first();;
        $response->assertOk();    
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'User search successful');
        $this->assertEquals($foundUser->email, $responseJson['users_list'][0]['email']);

        $response = $this->post("/api/search_user/",
        [
            'name'         => $user->email,
        ],
        [
            'Authorization' => 'Bearer ' . $userClerkToken
        ]);

        $response->assertOk();    
        $responseJson = $response->json();
        $this->assertEquals($responseJson['response_code'], '200');
        $this->assertEquals($responseJson['message'], 'There is no user matching the search');

        $userClerk->delete();
        $user->delete();
    }
     
}
