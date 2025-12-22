<?php

namespace Database\Factories;

use App\Models\Token;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'token' => $this->faker->sha256,
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
