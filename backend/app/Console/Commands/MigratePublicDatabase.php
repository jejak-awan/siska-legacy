<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigratePublicDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:public {--fresh : Drop all tables and re-run all migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for the public database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running public database migrations...');

        // Check if public database connection exists
        try {
            DB::connection('public')->getPdo();
            $this->info('Public database connection successful.');
        } catch (\Exception $e) {
            $this->error('Public database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Run migrations for public database
        $command = 'migrate';
        if ($this->option('fresh')) {
            $command = 'migrate:fresh';
        }

        $exitCode = Artisan::call($command, [
            '--database' => 'public',
            '--path' => 'database_public/migrations',
        ]);

        if ($exitCode === 0) {
            $this->info('Public database migrations completed successfully.');
        } else {
            $this->error('Public database migrations failed.');
            return 1;
        }

        return 0;
    }
}
