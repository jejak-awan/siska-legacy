<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get active academic year
        $tahunAjaran = TahunAjaran::where('is_aktif', true)->first();
        if (!$tahunAjaran) {
            $tahunAjaran = TahunAjaran::first();
        }

        // Get classes
        $kelas = Kelas::all();
        if ($kelas->isEmpty()) {
            $this->command->warn('No classes found. Please run KelasSeeder first.');
            return;
        }

        $siswaData = [];

        // Generate students for each class
        foreach ($kelas as $kelasItem) {
            $jumlahSiswa = $faker->numberBetween(25, 35); // 25-35 students per class
            
            for ($i = 0; $i < $jumlahSiswa; $i++) {
                // Create a new user for each student
                $namaLengkap = $faker->name();
                $username = 'siswa' . str_pad(self::$nisCounter, 6, '0', STR_PAD_LEFT);
                $user = \App\Models\User::create([
                    'username' => $username,
                    'email' => $username . '@example.com',
                    'password' => bcrypt('password'),
                    'role_type' => 'siswa',
                    'status' => 'aktif',
                ]);

                $siswaData[] = [
                    'user_id' => $user->id,
                    'nis' => $this->generateNIS($kelasItem->nama),
                    'nisn' => $faker->unique()->numerify('##########'),
                    'nama_lengkap' => $namaLengkap,
                    'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-15 years'),
                    'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
                    'alamat_lengkap' => $faker->address(),
                    'nomor_hp_siswa' => $faker->numerify('08##########'),
                    'email_siswa' => $user->email,
                    'kelas_id' => $kelasItem->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'status_siswa' => $faker->randomElement(['Aktif', 'Pindah', 'Lulus', 'DO', 'Mengundurkan Diri']),
                    'tanggal_masuk' => $faker->dateTimeBetween('-3 years', 'now'),
                    'nik' => $faker->unique()->numerify('################'), // 16 digit NIK
                    'no_kk' => $faker->numerify('################'), // 16 digit KK
                    'kelas' => $kelasItem->nama_kelas, // Kelas untuk compatibility
                    'angkatan' => $tahunAjaran->tahun_mulai, // Angkatan berdasarkan tahun ajaran
                    'kelurahan' => $faker->city(), // Kelurahan
                    'kecamatan' => $faker->city(), // Kecamatan
                    'kabupaten_kota' => $faker->city(), // Kabupaten/Kota
                    'provinsi' => $faker->state(), // Provinsi
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert students in batches
        $chunks = array_chunk($siswaData, 100);
        foreach ($chunks as $chunk) {
            Siswa::insert($chunk);
        }

        $this->command->info('Siswa seeder completed successfully! Generated ' . count($siswaData) . ' students.');
    }

    private static $nisCounter = 1000;

    /**
     * Generate NIS based on class name
     */
    private function generateNIS($kelasNama)
    {
        $prefix = '';
        if (strpos($kelasNama, 'X') !== false) {
            $prefix = '2024';
        } elseif (strpos($kelasNama, 'XI') !== false) {
            $prefix = '2023';
        } elseif (strpos($kelasNama, 'XII') !== false) {
            $prefix = '2022';
        } else {
            $prefix = '2024';
        }

        // Generate unique NIS using incremental counter (max 10 characters)
        do {
            $nis = $prefix . str_pad(self::$nisCounter, 6, '0', STR_PAD_LEFT);
            self::$nisCounter++;
        } while (Siswa::where('nis', $nis)->exists());

        return $nis;
    }
}
