<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $challenge = new Challenge();

        $challenge->player1_user_id = 2;
        $challenge->player2_user_id = 3;
        $challenge->score = json_encode(
        [
            "player1_set1" => 6,
            "player2_set1" => 3,
            "player1_set2" => 4,
            "player2_set2" => 6,
            "player1_set3" => 6,
            "player2_set3" => 2
        ]);

        $challenge->save();

        $challenge = new Challenge();

        $challenge->player1_user_id = 2;
        $challenge->player2_user_id = 4;
        $challenge->score = json_encode(
        [
            "player1_set1" => 6,
            "player2_set1" => 1,
            "player1_set2" => 4,
            "player2_set2" => 6,
            "player1_set3" => 6,
            "player2_set3" => 4
        ]);

        $challenge->save();

        $challenge = new Challenge();

        $challenge->player1_user_id = 5;
        $challenge->player2_user_id = 6;
        $challenge->score = json_encode(
        [
            "player1_set1" => 6,
            "player2_set1" => 1,
            "player1_set2" => 1,
            "player2_set2" => 6,
            "player1_set3" => 6,
            "player2_set3" => 1
        ]);

        $challenge->save();

        $challenge = new Challenge();

        $challenge->player1_user_id = 3;
        $challenge->player2_user_id = 4;
        $challenge->score = json_encode(
        [
            "player1_set1" => 4,
            "player2_set1" => 6,
            "player1_set2" => 1,
            "player2_set2" => 6
        ]);

        $challenge->save();
    }
}
