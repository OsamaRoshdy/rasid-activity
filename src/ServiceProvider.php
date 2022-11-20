<?php

namespace Rasid\Activity;

use Rasid\Activity\Console\UserActivityDelete;
use Rasid\Activity\Console\UserActivityInstall;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/activity.php';
    const MIGRATION_PATH = __DIR__ . '/../migrations';


    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('activity.php')
        ], 'config');

        $this->publishes([
            self::MIGRATION_PATH => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'activity'
        );

        $this->commands([UserActivityInstall::class, UserActivityDelete::class]);
    }

}
