<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SetUpDevEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dev environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('Step 1: Checking and loading environment configuration...');
        $this->checkForEnvFile();
        $this->loadEnvConfigAtRuntime();
        $this->info('Environment configuration loaded successfully.');

        $this->warn('Step 2: Migrating all tables into database...');
        $this->call('migrate:reset');
        $this->call('migrate');

        $this->warn('Step 3: Seeding dummy data for development...');
        $this->call('db:seed', ['--class' => 'DatabaseSeeder']);
        // optimizing stuffs
        $this->warn('Step 5: Optimizing...');
        $this->call('optimize');

        // running `composer dump-autoload`
        $this->warn('Step 6: Composer autoload...');
        shell_exec('composer dump-autoload');

        $this->warn('Done!');
    }

    /**
     *  Checking .env file and if not found then create .env file.
     *  Then ask for database name, password & username to set
     *  On .env file so that we can easily migrate to our db.
     *
     * @return void
     */
    protected function checkForEnvFile()
    {
        $envExists = File::exists(base_path() . '/.env');

        if (! $envExists) {
            $this->info('Your environment configuration file is missing. Please copy it from .env.example before running this command!');
            return;
        }
        $currentEnv = config('app.env');
        if ($currentEnv === 'production') {
            $this->info('This command was cancelled because of ' . strtoupper($currentEnv) . ' environment.');
            return;
        }

        $this->info('Great! your environment configuration file already exists.');

        $this->call('key:generate');
    }

    /**
     * Load `.env` config at runtime.
     *
     * @return void
     */
    protected function loadEnvConfigAtRuntime()
    {
        $this->warn('Loading configs...');

        /* environment directly checked from `.env` so changing in config won't reflect */
        app()['env'] = $this->getEnvAtRuntime('APP_ENV');

        /* setting for the first time and then `.env` values will be incharged */
        config(['database.connections.mysql.database' => $this->getEnvAtRuntime('DB_DATABASE')]);
        config(['database.connections.mysql.username' => $this->getEnvAtRuntime('DB_USERNAME')]);
        config(['database.connections.mysql.password' => $this->getEnvAtRuntime('DB_PASSWORD')]);
        DB::purge('mysql');

        $this->info('Configuration loaded..');
    }

    /**
     * Check key in `.env` file because it will help to find values at runtime.
     *
     * @param string $key
     *
     * @return bool|string
     */
    protected static function getEnvAtRuntime(string $key)
    {
        $path = base_path() . '/.env';
        $data = file($path);

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0 && str_contains($key, $rowValues[0])) {
                    return $rowValues[1];
                }
            }
        }

        return false;
    }
}
