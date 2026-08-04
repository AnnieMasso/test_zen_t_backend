<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agent>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => fake()->name(),
            'numero' => fake()->unique()->phoneNumber(), 
            'password' => bcrypt('password'), 
            // 'remember_token' => Str::random(10),
        ];
    }

}
