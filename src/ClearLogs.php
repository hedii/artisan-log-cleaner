<?php

namespace Hedii\ArtisanLogCleaner;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear {--keep-last : Whether the last log file should be kept}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove every log files in the log directory';

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $disk
     */
    public function __construct(private Filesystem $disk)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = $this->getLogFiles();

        if ($this->option('keep-last') && $files->count() >= 1) {
            $files->shift();
        }

        $deleted = $this->delete($files);

        if (! $deleted) {
            $this->info('There was no log file to delete in the log folder');
        } elseif ($deleted == 1) {
            $this->info('1 log file has been deleted');
        } else {
            $this->info($deleted . ' log files have been deleted');
        }
    }

    /**
     * Get a collection of log files sorted by their last modification date.
     */
    private function getLogFiles(): Collection
    {
        return Collection::make(
            $this->disk->allFiles(storage_path('logs'))
        )->sortBy('mtime');
    }

    /**
     * Delete the given files.
     */
    private function delete(Collection $files): int
    {
        return $files->each(function ($file) {
            $this->disk->delete($file);
        })->count();
    }
}
