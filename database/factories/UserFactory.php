<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'twitter_id' => Str::random(32),
            'name' => $this->faker->name(),
            'screen_name' => $this->faker->userName(),
            'profile_picture_url' => $this->faker->url(),
            'access_token' => Str::random(32),
            'access_token_secret' => Str::random(32),
            'report_schedule' => now(),
        ];
    }
}
