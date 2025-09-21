<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core seeders
            RoleSeeder::class,
            TahunAjaranSeeder::class,
            AdminUserSeeder::class,
            
            // Character assessment seeders
            CharacterDimensionSeeder::class,
            // CharacterIndicatorSeeder::class, // Skipped - table doesn't exist
            
            // Academic seeders
            KelasSeeder::class,
            MataPelajaranSeeder::class,
            SiswaSeeder::class,
            
            // Activity seeders
            KategoriKreditPoinSeeder::class,
            PresensiSeeder::class,
            KreditPoinSeeder::class,
            CharacterAssessmentSeeder::class,
            
            // Organization seeders
            OSISKegiatanSeeder::class,
            EkstrakurikulerSeeder::class,
            
            // Content seeders
            GallerySeeder::class,
            ContentSeeder::class,
            
            // Permission seeder
            PermissionSeeder::class,
            
            // Other seeders
            KonselingSeeder::class,
            HomeVisitSeeder::class,
            ReferenceDataSeeder::class,
        ]);
    }
}