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
            RoleSeeder::class,
            TahunAjaranSeeder::class,
            AdminUserSeeder::class,
            KategoriKreditPoinSeeder::class,
            KonselingSeeder::class,
            HomeVisitSeeder::class,
            OSISKegiatanSeeder::class,
            EkstrakurikulerSeeder::class,
        ]);
    }
}