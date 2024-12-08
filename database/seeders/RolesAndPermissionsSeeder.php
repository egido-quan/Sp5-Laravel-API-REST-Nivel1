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

        $editPlayers = Permission::create(['name' => 'edit players']);
        $viewPlayers = Permission::create(['name' => 'view players']);

        $editChallenges = Permission::create(['name' => 'edit challenges']);
        $viewChallenges = Permission::create(['name' => 'view challenges']);

        $adminRole->givePermissionTo($editPlayers, $viewPlayers, $editChallenges, $viewChallenges);
        $userRole->givePermissionTo($viewPlayers, $viewChallenges);
    }
}
