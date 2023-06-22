<?php

namespace App\Http\Controllers\Companies\Users;

use App\Http\Controllers\Controller;
use App\Library\Services\UserService;
use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * The IndexController constructor.
     */
    public function __construct(protected UserService $service)
    {
        //
    }

    /**
     * Get all users that belong to a company.
     */
    public function __invoke(Company $company): LengthAwarePaginator
    {
        $this->authorize('viewAnyUser', $company);

        $translations = $this->service->index($company->users())
            ->paginate()
            ->appends(request()->query());

        return resource($translations);
    }
}
