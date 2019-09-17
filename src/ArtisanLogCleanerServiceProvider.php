<?php

namespace Hedii\ArtisanLogCleaner;

use Illuminate\Support\ServiceProvider;

class ArtisanLogCleanerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([ClearLogs::class]);
    }
}
