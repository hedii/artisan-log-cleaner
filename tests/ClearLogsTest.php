<?php

namespace Hedii\ArtisanLogCleaner\Tests;

class ClearLogsTest extends TestCase
{
    /** @test */
    public function it_should_delete_all_files_in_log_directory(): void
    {
        $this->createLogFile(['file1.log', 'file2.log']);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');

        $this->artisan('log:clear');

        $this->assertFileDoesNotExist($this->logDirectory . '/file1.log');
        $this->assertFileDoesNotExist($this->logDirectory . '/file2.log');
    }

    /** @test */
    public function it_should_not_delete_dot_files_in_log_directory(): void
    {
        $this->createLogFile(['file1.log', 'file2.log']);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');

        $this->artisan('log:clear');

        $this->assertFileExists($this->logDirectory . '/.gitignore');
        $this->assertFileDoesNotExist($this->logDirectory . '/file1.log');
        $this->assertFileDoesNotExist($this->logDirectory . '/file2.log');
    }

    /** @test */
    public function it_should_keep_the_last_log_file_if_the_option_is_provided(): void
    {
        touch($this->logDirectory . '/file1.log', time() - 3600);
        touch($this->logDirectory . '/file2.log', time() - 4600);
        touch($this->logDirectory . '/file3.log', time() - 5600);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');
        $this->assertFileExists($this->logDirectory . '/file3.log');

        $this->artisan('log:clear', ['--keep-last' => true]);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileDoesNotExist($this->logDirectory . '/file2.log');
        $this->assertFileDoesNotExist($this->logDirectory . '/file3.log');
    }

    /** @test */
    public function it_should_keep_the_last_log_file_if_the_option_is_with_only_one_file(): void
    {
        touch($this->logDirectory . '/file1.log', time() - 3600);

        $this->assertFileExists($this->logDirectory . '/file1.log');

        $this->artisan('log:clear', ['--keep-last' => true]);

        $this->assertFileExists($this->logDirectory . '/file1.log');
    }

    /** @test */
    public function it_should_keep_the_specified_log_file_if_the_option_is_keep_specified_files(): void
    {
        touch($this->logDirectory . '/file1.log', time() - 3600);
        touch($this->logDirectory . '/file2.log', time() - 3600);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');

        $this->artisan('log:clear', ['--keep' => ['file2']]);

        $this->assertFileDoesNotExist($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');
    }

    /** @test */
    public function it_should_return_zero_even_if_there_is_no_log_file(): void
    {
        $this
            ->artisan('log:clear')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_return_zero_with_the_keep_last_option_even_if_there_is_no_log_file(): void
    {
        $this
            ->artisan('log:clear', ['--keep-last' => true])
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_display_the_correct_message_when_no_log_file_has_been_deleted(): void
    {
        $this
            ->artisan('log:clear')
            ->expectsOutput('There was no log file to delete in the log folder')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_display_the_correct_message_when_one_log_file_has_been_deleted(): void
    {
        $this->createLogFile(['file1.log', 'file2.log']);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');

        $this
            ->artisan('log:clear', ['--keep-last' => true])
            ->expectsOutput('1 log file has been deleted')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_display_the_correct_message_when_more_than_one_log_file_has_been_deleted(): void
    {
        $this->createLogFile(['file1.log', 'file2.log', 'file3.log']);

        $this->assertFileExists($this->logDirectory . '/file1.log');
        $this->assertFileExists($this->logDirectory . '/file2.log');
        $this->assertFileExists($this->logDirectory . '/file3.log');

        $this
            ->artisan('log:clear', ['--keep-last' => true])
            ->expectsOutput('2 log files have been deleted')
            ->assertExitCode(0);
    }
}
