<?php

namespace App\Http\Controllers\Translations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Translations\ExportRequest;
use App\Library\Services\AwsService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * The ExportController constructor.
     */
    public function __construct(protected AwsService $service)
    {
        //
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(ExportRequest $request): StreamedResponse
    {
        $path = decrypt($request->path);

        return Storage::disk(config('filesystems.default'))
            ->download($path, $request->filename);
    }
}
