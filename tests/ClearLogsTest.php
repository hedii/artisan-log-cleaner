<?php

namespace Hedii\ArtisanLogCleaner\Tests;

class ClearLogsTest extends TestCase
{
    public function test_it_should_delete_all_files_in_log_directory()
    {
        $this->createLogFile(['file1.log', 'file2.log']);

        if (! $this->artisan('log:clear')) {
            $this->assertFileNotExists($this->logDirectory . '/file1.log');
            $this->assertFileNotExists($this->logDirectory . '/file2.log');
        } else {
            $this->assertTrue(false, 'this test failed...');
        }
    }

    public function test_it_should_not_delete_dot_files_in_log_directory()
    {
        $this->createLogFile(['file1.log', 'file2.log']);

        $this->artisan('log:clear');

        if (! $this->artisan('log:clear')) {
            $this->assertFileExists($this->logDirectory . '/.gitignore');
            $this->assertFileNotExists($this->logDirectory . '/file1.log');
            $this->assertFileNotExists($this->logDirectory . '/file2.log');
        } else {
            $this->assertTrue(false, 'this test failed...');
        }
    }

    public function test_it_should_keep_the_last_log_file_if_the_option_is_provided()
    {
        touch($this->logDirectory . '/file1.log', time() - 3600);
        touch($this->logDirectory . '/file2.log', time() - 4600);
        touch($this->logDirectory . '/file3.log', time() - 5600);

        if (! $this->artisan('log:clear', ['--keep-last' => true])) {
            $this->assertFileExists($this->logDirectory . '/file1.log');
            $this->assertFileNotExists($this->logDirectory . '/file2.log');
            $this->assertFileNotExists($this->logDirectory . '/file3.log');
        } else {
            $this->assertTrue(false, 'this test failed...');
        }
    }

    public function test_it_should_return_zero_even_if_there_is_no_log_file()
    {
        $this->assertEquals(0, $this->artisan('log:clear'));
    }

    public function test_it_should_return_zero_with_the_keep_last_option_even_if_there_is_no_log_file()
    {
        $this->artisan('log:clear', ['--keep-last' => true]);
    }

    public function test_it_should_display_the_correct_message_when_no_log_file_has_been_deleted()
    {
        $command = $this->getMockedCommand();

        $this->expectInfoMessage('There was no log file to delete in the log folder', $command);

        $this->registerCommand($command);

        $this->artisan('log:clear');
    }

    public function test_it_should_display_the_correct_message_when_one_log_file_has_been_deleted()
    {
        $this->createLogFile(['file1.log', 'file2.log']);
        $command = $this->getMockedCommand();

        $this->expectInfoMessage('1 log file has been deleted', $command);

        $this->registerCommand($command);

        $this->artisan('log:clear', ['--keep-last' => true]);
    }

    public function test_it_should_display_the_correct_message_when_more_than_one_log_file_has_been_deleted()
    {
        $this->createLogFile(['file1.log', 'file2.log', 'file3.log']);
        $command = $this->getMockedCommand();

        $this->expectInfoMessage('2 log files have been deleted', $command);

        $this->registerCommand($command);

        $this->artisan('log:clear', ['--keep-last' => true]);
    }
}
