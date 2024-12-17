<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $last_player = Player::orderBy('ranking', 'desc')->first();
        $playerRanking = $last_player->ranking + 1;

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));
        return [
                'user_id'           => $user->id,
                'ranking'           => $playerRanking,
                'height'            => rand(100,250),
                'playing_hand'      => (rand(1,2) == 1) ? 'left' : 'right',
                'backhand_style'    => (rand(1,2) == 1) ? 'one hand' : 'two hands',
                'briefing'          => $this->faker->sentence,
            ];        
    }
}
