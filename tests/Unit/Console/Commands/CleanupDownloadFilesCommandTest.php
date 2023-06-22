<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanupDownloadFilesCommandTest extends TestCase
{
    /** @test */
    public function it_can_cleanup_download_files(): void
    {
        $this->artisan('cleanup:download-files')
            ->expectsOutput('No files to prune.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_days_option(): void
    {
        $this->artisan('cleanup:download-files --days=1')
            ->expectsOutput('No files to prune.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_force_option(): void
    {
        config(['app.env' => 'production']);

        $this->artisan('cleanup:download-files --force')
            ->expectsOutput('No files to prune.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_days_and_force_option(): void
    {
        $this->artisan('cleanup:download-files --days=1 --force')
            ->expectsOutput('No files to prune.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_days_option_less_than_1(): void
    {
        $this->artisan('cleanup:download-files --days=0')
            ->expectsOutput('The days option must be at least 1 day.')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_days_option_less_than_1_and_force_option(): void
    {
        $this->artisan('cleanup:download-files --days=0 --force')
            ->expectsOutput('The days option must be at least 1 day.')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_can_cleanup_download_files_with_days_option_less_than_1_and_force_option_and_confirm(): void
    {
        $this->artisan('cleanup:download-files --days=0 --force')
            ->expectsOutput('The days option must be at least 1 day.')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_deletes_old_files_only(): void
    {
        Storage::shouldReceive('disk')
            ->once()
            ->with(config('filesystems.default'))
            ->andReturnSelf();

        // Mock the files method to return a list of files
        Storage::shouldReceive('files')
            ->once()
            ->with('exports/translations')
            ->andReturn([
                'exports/translations/old.txt',
                'exports/translations/new.txt',
            ]);

        // Mock the lastModified method to return a timestamp for each file
        Storage::shouldReceive('lastModified')
            ->with('exports/translations/old.txt')
            ->andReturn(now()->subDays(2)->timestamp); // old file

        Storage::shouldReceive('lastModified')
            ->with('exports/translations/new.txt')
            ->andReturn(now()->timestamp); // new file

        // Mock the delete method to return true for the old file
        Storage::shouldReceive('delete')
            ->once()
            ->with('exports/translations/old.txt')
            ->andReturnTrue();

        $this->artisan('cleanup:download-files --days=1')
            ->expectsOutput('Pruning download files older than 1 days...')
            ->expectsOutput('Found 1 files to prune.')
            ->expectsOutput('Pruned 1 files.')
            ->assertExitCode(0);
    }
}
