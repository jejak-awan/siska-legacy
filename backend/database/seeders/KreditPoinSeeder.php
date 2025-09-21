<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KreditPoin;
use App\Models\KategoriKreditPoin;
use App\Models\Siswa;
use App\Models\User; // For pelapor_id
use Carbon\Carbon;
use Faker\Factory as Faker;

class KreditPoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KreditPoin::truncate(); // Clear existing data

        $faker = Faker::create('id_ID');

        $siswa = Siswa::where('status_siswa', 'Aktif')->get();
        $kategori = KategoriKreditPoin::all();
        $guruUsers = User::where('role_type', 'guru')->get();
        
        if ($siswa->isEmpty()) {
            $this->command->warn('No active students found. Please run SiswaSeeder first.');
            return;
        }

        if ($kategori->isEmpty()) {
            $this->command->warn('No credit point categories found. Please run KategoriKreditPoinSeeder first.');
            return;
        }

        if ($guruUsers->isEmpty()) {
            $this->command->warn('No guru users found. Please run AdminUserSeeder or a seeder that creates guru users first.');
            return;
        }

        $kreditPoinData = [];
        $startDate = Carbon::now()->subDays(60); // Last 60 days
        $endDate = Carbon::now();

        foreach ($siswa as $siswaItem) {
            // Generate 2-5 credit point records per student
            $jumlahRecord = rand(2, 5);
            
            for ($i = 0; $i < $jumlahRecord; $i++) {
                $kategoriItem = $kategori->random();
                $tanggal = Carbon::createFromFormat('Y-m-d', $startDate->format('Y-m-d'))
                    ->addDays(rand(0, $startDate->diffInDays($endDate)));

                $poin = $kategoriItem->jenis == 'positif' ? rand(5, 20) : rand(-5, -20);
                
                $kreditPoinData[] = [
                    'siswa_id' => $siswaItem->id,
                    'kategori_id' => $kategoriItem->id,
                    'nilai' => $poin,
                    'deskripsi' => $kategoriItem->nama ?? 'Kredit Poin',
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'pelapor_id' => $guruUsers->random()->id,
                    'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
                    'catatan_admin' => $faker->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in batches
        $chunks = array_chunk($kreditPoinData, 100);
        foreach ($chunks as $chunk) {
            KreditPoin::insert($chunk);
        }

        $this->command->info('Kredit Poin seeder completed successfully! Generated ' . count($kreditPoinData) . ' credit point records.');
    }

    /**
     * Generate keterangan based on category
     */
    private function generateKeterangan($kategoriNama)
    {
        $keteranganMap = [
            'Membantu Teman' => 'Membantu teman yang kesulitan dalam pelajaran',
            'Mengikuti Kegiatan Sekolah' => 'Aktif mengikuti kegiatan sekolah',
            'Prestasi Akademik' => 'Mendapat nilai bagus dalam ujian',
            'Prestasi Non-Akademik' => 'Juara dalam lomba ekstrakurikuler',
            'Terlambat' => 'Terlambat masuk sekolah',
            'Berkelahi' => 'Terlibat perkelahian dengan teman',
            'Bolos Sekolah' => 'Tidak masuk sekolah tanpa izin',
            'Melanggar Tata Tertib' => 'Melanggar peraturan sekolah',
        ];

        return $keteranganMap[$kategoriNama] ?? 'Kredit poin untuk ' . $kategoriNama;
    }
}
