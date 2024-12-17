<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'api']);

        $editPlayers = Permission::create(['name' => 'edit players', 'guard_name' => 'api']);
        $viewPlayers = Permission::create(['name' => 'view players', 'guard_name' => 'api']);

        $editChallenges = Permission::create(['name' => 'edit challenges', 'guard_name' => 'api']);
        $viewChallenges = Permission::create(['name' => 'view challenges', 'guard_name' => 'api']);

        $adminRole->givePermissionTo($editPlayers, $viewPlayers, $editChallenges, $viewChallenges);
        $userRole->givePermissionTo($viewPlayers, $viewChallenges);
    }
}
