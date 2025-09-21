<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active academic year
        $tahunAjaran = TahunAjaran::where('is_aktif', true)->first();
        if (!$tahunAjaran) {
            $tahunAjaran = TahunAjaran::first();
        }

        // Get teachers (wali kelas) from guru table
        $guru = \App\Models\Guru::all();
        if ($guru->isEmpty()) {
            $this->command->warn('No teachers found. Please run AdminUserSeeder first.');
            return;
        }

        $kelasData = [
            // Kelas X
            [
                'nama_kelas' => 'X IPA 1',
                'tingkat' => 10,
                'jurusan' => 'IPA',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas X IPA 1 - Kelas unggulan IPA',
            ],
            [
                'nama_kelas' => 'X IPA 2',
                'tingkat' => 10,
                'jurusan' => 'IPA',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas X IPA 2',
            ],
            [
                'nama_kelas' => 'X IPS 1',
                'tingkat' => 10,
                'jurusan' => 'IPS',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas X IPS 1',
            ],
            [
                'nama_kelas' => 'X IPS 2',
                'tingkat' => 10,
                'jurusan' => 'IPS',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas X IPS 2',
            ],

            // Kelas XI
            [
                'nama_kelas' => 'XI IPA 1',
                'tingkat' => 11,
                'jurusan' => 'IPA',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XI IPA 1 - Kelas unggulan IPA',
            ],
            [
                'nama_kelas' => 'XI IPA 2',
                'tingkat' => 11,
                'jurusan' => 'IPA',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XI IPA 2',
            ],
            [
                'nama_kelas' => 'XI IPS 1',
                'tingkat' => 11,
                'jurusan' => 'IPS',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XI IPS 1',
            ],
            [
                'nama_kelas' => 'XI IPS 2',
                'tingkat' => 11,
                'jurusan' => 'IPS',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XI IPS 2',
            ],

            // Kelas XII
            [
                'nama_kelas' => 'XII IPA 1',
                'tingkat' => 12,
                'jurusan' => 'IPA',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XII IPA 1 - Kelas unggulan IPA',
            ],
            [
                'nama_kelas' => 'XII IPA 2',
                'tingkat' => 12,
                'jurusan' => 'IPA',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XII IPA 2',
            ],
            [
                'nama_kelas' => 'XII IPS 1',
                'tingkat' => 12,
                'jurusan' => 'IPS',
                'rombel' => '1',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XII IPS 1',
            ],
            [
                'nama_kelas' => 'XII IPS 2',
                'tingkat' => 12,
                'jurusan' => 'IPS',
                'rombel' => '2',
                'kapasitas' => 32,
                'jumlah_siswa' => 0,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'wali_kelas_id' => $guru->random()->id,
                'status' => 'aktif',
                'keterangan' => 'Kelas XII IPS 2',
            ],
        ];

        foreach ($kelasData as $data) {
            Kelas::create($data);
        }

        $this->command->info('Kelas seeder completed successfully!');
    }
}
