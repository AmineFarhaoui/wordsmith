<?php

namespace App\Library\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Owowagency\LaravelMedia\Managers\MediaManager;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserService
{
    /**
     * The UserManager constructor.
     */
    public function __construct(private MediaManager $mediaManager)
    {
        //
    }

    /**
     * Create a length aware paginator for the given subject using the Spatie
     * Query Builder.
     */
    public function index(Relation|Builder|string $subject): QueryBuilder
    {
        return QueryBuilder::for($subject)
            ->allowedFilters([
                'first_name',
                'last_name',
                'full_name',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'id',
                AllowedSort::field('firstName', 'first_name'),
                AllowedSort::field('lastName', 'last_name'),
                AllowedSort::field('fullName', 'full_name'),
                AllowedSort::field('createdAt', 'created_at'),
                AllowedSort::field('updatedAt', 'updated_at'),
            ]);
    }

    /**
     * Creates a user.
     */
    public function create(array $data): User
    {
        return $this->save(new User(), $data);
    }

    /**
     * Updates a user.
     */
    public function update(User $user, array $data): User
    {
        return $this->save($user, $data);
    }

    /**
     * Save the user model.
     */
    private function save(User $user, array $data): User
    {
        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill(Arr::only($data, $user->getFillable()));

        $user->save();

        if (array_key_exists('profile_picture', $data)) {
            $this->mediaManager->upload($user, $data['profile_picture'], collection: 'profile_picture');
        }

        return $user;
    }
}
