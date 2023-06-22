<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RequiredTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
    }
}
