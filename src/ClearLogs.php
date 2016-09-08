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
     * A filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $disk;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $disk
     */
    public function __construct(Filesystem $disk)
    {
        parent::__construct();

        $this->disk = $disk;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = $this->getLogFiles();

        if ($this->option('keep-last') && $files->count() > 1) {
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
     *
     * @return \Illuminate\Support\Collection
     */
    private function getLogFiles()
    {
        return collect(
            $this->disk->allFiles(storage_path('logs'))
        )->sortBy('mtime');
    }

    /**
     * Delete the given files.
     *
     * @param \Illuminate\Support\Collection $files
     * @return int
     */
    private function delete(Collection $files)
    {
        return $files->each(function ($file) {
            $this->disk->delete($file);
        })->count();
    }
}
