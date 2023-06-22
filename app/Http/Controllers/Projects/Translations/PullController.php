<?php

namespace App\Http\Controllers\Projects\Translations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\Translations\PullRequest;
use App\Library\Services\TranslationService;
use App\Library\Translations\ExportFactory;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class PullController extends Controller
{
    /**
     * The PullController constructor.
     */
    public function __construct(protected TranslationService $service)
    {
        //
    }

    /**
     * Retrieve all translations with values filtered on tags.
     */
    public function __invoke(PullRequest $request, Project $project): JsonResponse
    {
        if (current_user()->currentAccessToken()->cant('pull')) {
            return forbidden();
        }

        $this->authorize('view', [$project, current_user()]);

        $data = $request->validated();

        $translations = $this->service->index($project->translations())
            ->with(['translationValues' => fn ($query) => $query->where('language', $data['language'])])
            ->orderBy('key')
            ->when(
                $data['tags'] ?? null,
                fn ($query, $tags) => $query->whereHas('tags', fn ($query) => $query->whereIn('slug', $tags)),
            )
            ->get();

        $export = ExportFactory::create($data['file_type'], $data['language'], $translations);

        $export->save();

        return ok([
            'url' => $export->getDownloadableUrl(),
            'file_name' => $export->getDownloadableFileName(),
        ]);
    }
}
