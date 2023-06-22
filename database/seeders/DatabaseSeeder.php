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
        $this->call(RequiredTablesSeeder::class);

        if (app()->environment(['production', 'testing'])) {
            return;
        }

        $this->call(UserSeeder::class);

        $this->call(FullContentSeeder::class);
    }
}
