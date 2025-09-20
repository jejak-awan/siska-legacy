<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Public\GeneralPost;
use App\Models\Public\Program;
use App\Models\Public\ProgramComponent;
use App\Models\Public\PublicActivity;

class PublicDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GeneralPostSeeder::class,
            ProgramSeeder::class,
            PublicActivitySeeder::class,
        ]);
    }
}
