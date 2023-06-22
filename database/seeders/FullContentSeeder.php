<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\Translation;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

class FullContentSeeder extends Seeder
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

        Company::factory(2)
            ->has(
                Project::factory(3)
                    ->has(
                        Translation::factory(5)
                            ->hasTranslationValues(1),
                    ),
            )
            ->hasUsers(3)
            ->create();
    }
}
