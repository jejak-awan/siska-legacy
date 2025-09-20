<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SeedPublicDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed:public {--class= : The class name of the root seeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the public database with records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding public database...');

        // Check if public database connection exists
        try {
            DB::connection('public')->getPdo();
            $this->info('Public database connection successful.');
        } catch (\Exception $e) {
            $this->error('Public database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Run seeders for public database
        $class = $this->option('class') ?: 'Database\\Seeders\\PublicDatabaseSeeder';
        
        $exitCode = Artisan::call('db:seed', [
            '--database' => 'public',
            '--class' => $class,
        ]);

        if ($exitCode === 0) {
            $this->info('Public database seeding completed successfully.');
        } else {
            $this->error('Public database seeding failed.');
            return 1;
        }

        return 0;
    }
}
