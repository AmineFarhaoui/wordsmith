<?php

namespace Database\Factories;

use App\Library\Enumerations\Role;
use App\Models\User;
use Database\Factories\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    use HasMedia;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->emailFaker(),
            'email_verified_at' => now(),
            'password' => '$2y$04$sdycBBTO4Ggwe.42ErCABu96MpR2rI/5pMmJSRUnqiINXUPwus6He', // Password1!
        ];
    }

    /**
     * Generate more unique email address. Because we validate email existence,
     * we need to use a domain which is online.
     */
    private function emailFaker(): string
    {
        return sprintf(
            '%s%s@owow.io',
            $this->faker->unique()->userName(),
            $this->faker->numberBetween(10000, 99999),
        );
    }

    /**
     * Make the user admin.
     */
    public function admin(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::ADMIN);
        });
    }
}
