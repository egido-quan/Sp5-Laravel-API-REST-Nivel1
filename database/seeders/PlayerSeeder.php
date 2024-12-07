<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 2; 


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 183;
        $player->playing_hand = "right";
        $player->backhand_style = "two hands";
        $player->briefing = "I started skiing but changed to tennis";

        $player->save();


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 185;
        $player->playing_hand = "right";
        $player->backhand_style = "two hands";
        $player->briefing = "I have my own style";

        $player->save();


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 180;
        $player->playing_hand = "right";
        $player->backhand_style = "two hands";
        $player->briefing = "Idemo !";

        $player->save();


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 179;
        $player->playing_hand = "right";
        $player->backhand_style = "two hands";
        $player->briefing = "I am a nice guy, but terrible on the court";

        $player->save();


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 194;
        $player->playing_hand = "right";
        $player->backhand_style = "two hands";
        $player->briefing = "I like jewlery";

        $player->save();


        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 186;
        $player->playing_hand = "left";
        $player->backhand_style = "two hands";
        $player->briefing = "I am Aussie";

        $player->save();

        
        $player = new Player();

        $player->user_id = $id ++;
        $player->ranking = $player->user_id - 1;
        $player->height = 178;
        $player->playing_hand = "right";
        $player->backhand_style = "one hand";
        $player->briefing = "Forza Italia";

        $player->save();
    }
}
