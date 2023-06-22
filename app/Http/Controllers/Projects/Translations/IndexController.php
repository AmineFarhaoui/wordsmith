<?php

namespace App\Http\Controllers\Projects\Translations;

use App\Http\Controllers\Controller;
use App\Library\Services\TranslationService;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * The IndexController constructor.
     */
    public function __construct(protected TranslationService $service)
    {
        //
    }

    /**
     * Retrieve all translations with values filtered on tags for a project.
     */
    public function __invoke(Project $project): LengthAwarePaginator
    {
        $this->authorize('view', $project);

        $translations = $this->service->index($project->translations())
            ->with(['tags', 'translationValues'])
            ->paginate()
            ->appends(request()->query());

        return resource($translations);
    }
}
