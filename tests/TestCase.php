<?php

namespace Hedii\ArtisanLogCleaner\Tests;

use Hedii\ArtisanLogCleaner\ArtisanLogCleanerServiceProvider;
use Hedii\ArtisanLogCleaner\ClearLogs;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * The log directory path.
     *
     * @var string
     */
    protected $logDirectory;

    /**
     * Executed before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->logDirectory = storage_path('logs');

        $this->deleteLogFiles();
    }

    /**
     * Executed after each test.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->deleteLogFiles();
    }

    /**
     * Load the command service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ArtisanLogCleanerServiceProvider::class];
    }

    /**
     * The mock of the command.
     *
     * @return \Mockery\MockInterface
     */
    protected function getMockedCommand()
    {
        return Mockery::mock(ClearLogs::class . '[info]', [
            new Filesystem()
        ]);
    }

    /**
     * The command info expectation.
     *
     * @param string $message
     * @param \Mockery\MockInterface $command
     */
    protected function expectInfoMessage($message, $command)
    {
        $command->shouldReceive('info')
            ->once()
            ->with($message);
    }

    /**
     * Register the mocked command.
     *
     * @param \Mockery\MockInterface $command
     */
    protected function registerCommand($command)
    {
        $this->app[Kernel::class]->registerCommand($command);
    }

    /**
     * Create fake log files in the test temporary directory.
     *
     * @param array|string $files
     */
    protected function createLogFile($files)
    {
        foreach ((array) $files as $file) {
            touch($this->logDirectory . '/' . $file);
        }
    }

    /**
     * Delete all fake log files int the test temporary directory.
     */
    private function deleteLogFiles()
    {
        foreach (glob($this->logDirectory . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}