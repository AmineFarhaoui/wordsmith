<?php

namespace App\Http\Controllers\Projects\Translations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\Translations\PushRequest;
use App\Library\Services\TranslationService;
use App\Library\Translations\ImportFactory;
use App\Library\Translations\RawTranslation;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class PushController extends Controller
{
    /**
     * The PushController constructor.
     */
    public function __construct(protected TranslationService $service)
    {
        //
    }

    /**
     * Push translations to the project with tags.
     */
    public function __invoke(PushRequest $request, Project $project): JsonResponse
    {
        abort_if(current_user()->currentAccessToken()->cant('push'), 403);

        $this->authorize('view', [$project, current_user()]);

        $data = $request->validated();

        try {
            $importer = ImportFactory::create($data['file_type'], $data['file']);

            $translations = $importer->data()
                ->when($data['tags'] ?? [], function (Collection $translations, array $tags) {
                    return $translations->map(fn (RawTranslation $t) => $t->setTags($tags));
                });

            $this->service->saveManyRawTranslations($project,
                $translations,
                $data['language'],
                $data['overwrite_existing_values'],
                $data['verify_translations'],
            );
        } catch (\Throwable $e) {
            app('sentry')->captureException($e);

            return bad_request(__('general.messages.error'));
        }

        return no_content();
    }
}
