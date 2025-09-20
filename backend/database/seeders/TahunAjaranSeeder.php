<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = [
            [
                'nama' => '2024/2025',
                'tahun_mulai' => 2024,
                'tahun_selesai' => 2025,
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2025-06-30',
                'is_aktif' => true,
                'keterangan' => 'Tahun ajaran aktif saat ini'
            ],
            [
                'nama' => '2023/2024',
                'tahun_mulai' => 2023,
                'tahun_selesai' => 2024,
                'tanggal_mulai' => '2023-07-15',
                'tanggal_selesai' => '2024-06-30',
                'is_aktif' => false,
                'keterangan' => 'Tahun ajaran sebelumnya'
            ],
            [
                'nama' => '2025/2026',
                'tahun_mulai' => 2025,
                'tahun_selesai' => 2026,
                'tanggal_mulai' => '2025-07-15',
                'tanggal_selesai' => '2026-06-30',
                'is_aktif' => false,
                'keterangan' => 'Tahun ajaran yang akan datang'
            ]
        ];

        foreach ($tahunAjaran as $ta) {
            TahunAjaran::updateOrCreate(
                ['nama' => $ta['nama']],
                $ta
            );
        }
    }
}