<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Enregistrez vos seeders manuels ici 👇
        $this->call([
            User::class,
            Agent::class,
        ]);
    }
}
