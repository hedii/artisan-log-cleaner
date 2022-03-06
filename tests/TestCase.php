<?php

namespace Hedii\ArtisanLogCleaner\Tests;

use Hedii\ArtisanLogCleaner\ArtisanLogCleanerServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * The log directory path.
     */
    protected string $logDirectory;

    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->logDirectory = storage_path('logs');

        $this->deleteLogFiles();
    }

    /**
     * Executed after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteLogFiles();
    }

    /**
     * Load the command service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [ArtisanLogCleanerServiceProvider::class];
    }

    /**
     * Create fake log files in the test temporary directory.
     */
    protected function createLogFile(array|string $files): void
    {
        foreach ((array) $files as $file) {
            touch($this->logDirectory . '/' . $file);
        }
    }

    /**
     * Delete all fake log files int the test temporary directory.
     */
    private function deleteLogFiles(): void
    {
        foreach (glob($this->logDirectory . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
