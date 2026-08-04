<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Agent extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\Agent::factory()->create([
            'name' => 'Agent',
            'numero' => '654177202',
            'password' => bcrypt('secret123'),
        ]);



    }
}
