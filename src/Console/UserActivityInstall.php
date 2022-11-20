<?php

namespace Rasid\Activity\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class UserActivityInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will publish config file and run a migration for user log activity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrationFile = "2022_11_16_000000_create_" . config('activity.table') ."_table.php";
        //config
        if (File::exists(config_path('activity.php'))) {
            $confirm = $this->confirm("activity.php config file already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishConfig();
                $this->info("config overwrite finished");
            } else {
                $this->info("skipped config publish");
            }
        } else {
            $this->publishConfig();
            $this->info("config published");
        }


        //migration
        if (File::exists(database_path("migrations/$migrationFile"))) {
            $confirm = $this->confirm("migration file already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishMigration();
                $this->info("migration overwrite finished");
            } else {
                $this->info("skipped migration publish");
            }
        } else {
            $this->publishMigration();
            $this->info("migration published");
        }

        $this->line('-----------------------------');
        if (!Schema::hasTable(config('activity.table'))) {
            $this->call('migrate');
        } else {
            $this->error('logs table already exist in your database. migration not run successfully');
        }

    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "Rasid\Activity\ServiceProvider",
            '--tag'      => 'config',
            '--force'    => true
        ]);
    }

    private function publishMigration()
    {
        $this->call('vendor:publish', [
            '--provider' => "Rasid\Activity\ServiceProvider",
            '--tag'      => 'migrations',
            '--force'    => true
        ]);
    }

}
