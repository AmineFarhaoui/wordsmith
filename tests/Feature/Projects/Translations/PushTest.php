<?php

namespace Tests\Feature\Projects\Translations;

use App\Models\CompanyProject;
use Illuminate\Http\UploadedFile;
use Tests\Feature\TestCase;

class PushTest extends TestCase
{
    /** @test */
    public function company_can_push_translations_using_i18next(): void
    {
        [$project, $companyToken] = $this->prepare();

        $data = $this->requestData();

        $response = $this->json(
            'POST',
            "projects/$project->id/translations/push",
            $data,
            ['Authorization' => 'Bearer '.$companyToken],
        );

        $this->assertResponse($response, 204, false);

        $translations = $project->translations()->with(['translationValues', 'tags'])->get();

        $this->assertJsonStructureSnapshot($translations->toJson());
    }

    public function prepare(): array
    {
        $companyProject = CompanyProject::factory()->create();

        $companyToken = $companyProject->company->createToken('company', ['push'])->plainTextToken;

        return [$companyProject->project, $companyToken];
    }

    private function requestData(): array
    {
        $file = UploadedFile::fake()->createWithContent(
            'en.json',
            file_get_contents(base_path('tests/Feature/Projects/Translations/data/en.json')),
        );

        return [
            'file' => $file,
            'file_type' => 'i18next',
            'language' => 'en',
            'overwrite_existing_values' => true,
            'verify_translations' => false,
            'tags' => ['test'],
        ];
    }
}
