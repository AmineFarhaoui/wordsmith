<?php

namespace Tests\Unit\Library\Services;

use App\Library\Services\RelationService;
use App\Models\User;
use Tests\Support\Models\TestModel;
use Tests\TestCase;

class RelationServiceTest extends TestCase
{
    /** @test */
    public function it_syncs_and_detaches_relations(): void
    {
        $users = User::factory(4)->create();

        $testModel = TestModel::factory()->create();

        // This relation should have different pivot data.
        $testModel->users()->attach($users[1], ['number' => 1]);

        // This relation should be detached.
        $testModel->users()->attach($users[2], ['number' => 1]);

        // This relation should be untouched.
        $testModel->users()->attach($users[3], ['number' => 1]);

        app(RelationService::class)->syncAndDetachRelations($testModel, 'users', [
            'sync' => [
                ['id' => $users[0]->id, 'number' => 3],
                ['id' => $users[1]->id, 'number' => 2],
            ],
            'detach' => [
                $users[2]->id,
            ],
        ]);

        // This relation should be attached.
        $this->assertDatabaseHas('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $users[0]->id,
            'number' => 3,
        ]);

        // This relation should have different pivot data.
        $this->assertDatabaseHas('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $users[1]->id,
            'number' => 2,
        ]);

        // This relation should be detached.
        $this->assertDatabaseMissing('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $users[2]->id,
        ]);

        // This relation should be untouched.
        $this->assertDatabaseHas('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $users[3]->id,
            'number' => 1,
        ]);
    }

    /** @test */
    public function it_attaches_relations(): void
    {
        $users = User::factory(2)->create();

        $testModel = TestModel::factory()->create();

        app(RelationService::class)->syncRelations($testModel, 'users', $users->map(fn ($user) => [
            'id' => $user->id,
            'number' => $user->id * 2,
        ])->toArray());

        $users->each(fn ($user) => $this->assertDatabaseHas('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $user->id,
            'number' => $user->id * 2,
        ]));
    }

    /** @test */
    public function it_detaches_relations(): void
    {
        $user = User::factory()->create();

        $testModel = TestModel::factory()->create();

        $testModel->users()->attach($user, [
            'number' => 1,
        ]);

        app(RelationService::class)->detachRelations($testModel, 'users', [$user->id]);

        $this->assertDatabaseMissing('test_model_user', [
            'test_model_id' => $testModel->id,
            'user_id' => $user->id,
        ]);
    }
}
