<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@admin')->first();
        $user->assignRole('admin');

        $user = User::where('email', 'jannik@sinner')->first();
        $user->assignRole('user');

        $user = User::where('email', 'daniil@medvedev')->first();
        $user->assignRole('user');

        $user = User::where('email', 'novak@djokovic')->first();
        $user->assignRole('user');

        $user = User::where('email', 'andrei@rublev')->first();
        $user->assignRole('user');

        $user = User::where('email', 'alexander@zverev')->first();
        $user->assignRole('user');

        $user = User::where('email', 'jack@draper')->first();
        $user->assignRole('user');

        $user = User::where('email', 'lorenzo@musetti')->first();
        $user->assignRole('user');


    }
}