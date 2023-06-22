<?php

namespace Database\Seeders;

use App\Library\Enumerations\Role;
use App\Models\User;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use SeedsMedia;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment(['production', 'testing'])) {
            return;
        }

        $user = User::factory()->create([
            'email' => 'user@owow.io',
        ]);

        // $this->seedMedia($user, 'profile_picture');

        $admin = User::factory()->create([
            'email' => 'admin@owow.io',
        ]);

        // $this->seedMedia($admin, 'profile_picture');

        $admin->assignRole(Role::ADMIN);
    }
}
