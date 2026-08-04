<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Crée un administrateur spécifique
        \App\Models\User::factory()->create([
            'name' => 'User',
            'numero' => '655477203',
            'password' => bcrypt('secret123'),
        ]);




    }
}
