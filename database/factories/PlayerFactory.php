<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
                'height'            => rand(100,250),
                'playing_hand'      => (rand(1,2) == 1) ? 'left' : 'right',
                'backhand_style'    => (rand(1,2) == 1) ? 'one hand' : 'two hands',
                'briefing'          => $this->faker->sentence,
            ];        
    }
}
