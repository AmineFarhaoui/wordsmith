<?php

namespace Tests\Feature\Projects\Translations;

use App\Models\CompanyProject;
use App\Models\Translation;
use App\Models\TranslationValue;
use Illuminate\Support\Arr;
use Tests\Feature\TestCase;

class PullTest extends TestCase
{
    /** @test */
    public function company_can_pull_translations(): void
    {
        [$companyToken, $project] = $this->prepare();

        $response = $this->json(
            'GET',
            "projects/$project->id/translations/pull?".Arr::query([
                'sort' => 'id',
                'language' => 'en',
                'file_type' => 'json',
            ]),
            headers: [
                'Authorization' => 'Bearer '.$companyToken,
            ],
        );

        $this->assertResponse($response);
    }

    /** @test */
    public function company_can_pull_translations_filtered_on_tags(): void
    {
        [$companyToken, $project] = $this->prepare();

        $response = $this->json(
            'GET',
            "projects/$project->id/translations/pull?".Arr::query([
                'sort' => 'id',
                'language' => 'en',
                'file_type' => 'json',
                'tags' => [
                    'android',
                ],
            ]),
            headers: [
                'Authorization' => 'Bearer '.$companyToken,
            ],
        );

        $this->assertResponse($response);
    }

    /**
     * Prepares for test.
     */
    public function prepare(): array
    {
        $companyProject = CompanyProject::factory()->create();

        Translation::factory()
            ->has(
                TranslationValue::factory(),
            )
            ->create([
                'project_id' => $companyProject->project->id,
            ]);

        Translation::factory()
            ->has(
                TranslationValue::factory([
                    'value' => json_encode(['Admin', 'Moderator']),
                ]),
            )
            ->create([
                'project_id' => $companyProject->project->id,
                'is_nested' => true,
            ]);

        $companyToken = $companyProject->company->createToken('company', ['pull'])->plainTextToken;

        return [$companyToken, $companyProject->project];
    }
}
