<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupDownloadFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:download-files {--days=1 : The number of days to keep download files} {--force : Force the operation to run in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old download files (exports) which are older than the given days.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');

        if ($days < 1) {
            $this->error('The days option must be at least 1 day.');

            return Command::FAILURE;
        }

        if (app()->environment('production')
            && ! $this->option('force')
            && ! $this->confirm('Do you wish to continue?')) {
            $this->info('Command aborted.');

            return Command::SUCCESS;
        }

        $disk = Storage::disk(config('filesystems.default'));

        $timestamp = now()->subDays($days)->timestamp;

        $this->info('Pruning download files older than '.$days.' days...');

        $files = collect($disk->files('exports/translations'))
            ->filter(fn ($file) => $disk->lastModified($file) < $timestamp);

        if ($files->isEmpty()) {
            $this->info('No files to prune.');

            return Command::SUCCESS;
        }

        $this->info('Found '.$files->count().' files to prune.');

        $files->each(fn ($file) => $disk->delete($file));

        $this->info('Pruned '.$files->count().' files.');

        return Command::SUCCESS;
    }
}
