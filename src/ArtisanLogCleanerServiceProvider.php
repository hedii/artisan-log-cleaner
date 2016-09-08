<?php

namespace Hedii\ArtisanLogCleaner;

use Illuminate\Support\ServiceProvider;

class ArtisanLogCleanerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([ClearLogs::class]);
    }
}