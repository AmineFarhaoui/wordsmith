<?php

namespace Tests\Feature\Projects\Translations;

use App\Models\CompanyProject;
use App\Models\CompanyUser;
use App\Models\Translation;
use App\Models\TranslationValue;
use Tests\Feature\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function user_can_index(): void
    {
        [$user, $project] = $this->prepare();

        $response = $this->makeRelationRequest($project, new Translation(), $user);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_filtered_by_language(): void
    {
        [$user, $project] = $this->prepare();

        $parameters = [
            'filter' => [
                'translation_values.language' => 'nl',
            ],
        ];

        $response = $this->makeRelationRequest($project, new Translation(), $user, $parameters);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_filtered_by_tags(): void
    {
        [$user, $project] = $this->prepare();

        $parameters = [
            'filter' => [
                'tags.name' => 'api',
            ],
        ];

        $response = $this->makeRelationRequest($project, new Translation(), $user, $parameters);

        $this->assertResponse($response);
    }

    /**
     * Prepares for test.
     */
    public function prepare(): array
    {
        $companyUser = CompanyUser::factory()
            ->create();

        $companyProject = CompanyProject::factory()
            ->create([
                'company_id' => $companyUser->company_id,
            ]);

        $translation = Translation::factory()
            ->has(
                TranslationValue::factory()->state([
                    'language' => 'en',
                    'verified_at' => null,
                ]),
            )
            ->create([
                'project_id' => $companyProject->project_id,
            ]);

        $translation->attachTag('api', 'translation');

        Translation::factory()
            ->has(
                TranslationValue::factory()->state([
                    'language' => 'nl',
                    'verified_at' => null,
                ]),
            )
            ->create([
                'project_id' => $companyProject->project_id,
            ]);

        return [$companyUser->user, $companyProject->project];
    }
}
