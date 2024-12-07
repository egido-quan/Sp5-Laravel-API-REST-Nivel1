<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = new User();

        $user->name = "admin";
        $user->surname = "admin";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "admin";

        //$user->assignRole('admin');
        $user->save();


        $user = new User();

        $user->name = "Jannik";
        $user->surname = "Sinner";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();


        $user = new User();

        $user->name = "Daniil";
        $user->surname = "Medvedev";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();


        $user = new User();

        $user->name = "Novak";
        $user->surname = "Djokovic";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();


        $user = new User();

        $user->name = "Andrei";
        $user->surname = "Rublev";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();


        $user = new User();

        $user->name = "Alexander";
        $user->surname = "Zverev";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();


        $user = new User();

        $user->name = "Jack";
        $user->surname = "Draper";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();

        
        $user = new User();

        $user->name = "Lorenzo";
        $user->surname = "Musetti";
        $user->email = $user->name . "@" . $user->surname;
        $user->password = "xxx";

        //$user->assignRole('player');
        $user->save();
    }
}
