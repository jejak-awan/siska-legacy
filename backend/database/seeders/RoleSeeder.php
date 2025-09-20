<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator sistem dengan akses penuh',
                'level' => 1,
                'permissions' => [
                    'users.create', 'users.read', 'users.update', 'users.delete',
                    'guru.create', 'guru.read', 'guru.update', 'guru.delete',
                    'siswa.create', 'siswa.read', 'siswa.update', 'siswa.delete',
                    'kelas.create', 'kelas.read', 'kelas.update', 'kelas.delete',
                    'dashboard.admin', 'reports.all', 'settings.manage'
                ]
            ],
            [
                'name' => 'guru',
                'description' => 'Guru dengan akses ke kelas yang diampu',
                'level' => 3,
                'permissions' => [
                    'siswa.read', 'siswa.update',
                    'kelas.read',
                    'presensi.create', 'presensi.read', 'presensi.update',
                    'kredit_poin.create', 'kredit_poin.read',
                    'dashboard.guru'
                ]
            ],
            [
                'name' => 'siswa',
                'description' => 'Siswa dengan akses terbatas',
                'level' => 5,
                'permissions' => [
                    'profile.read', 'profile.update',
                    'presensi.read',
                    'kredit_poin.read',
                    'dashboard.siswa'
                ]
            ],
            [
                'name' => 'wali_kelas',
                'description' => 'Wali kelas dengan akses ke kelas yang dibimbing',
                'level' => 3,
                'permissions' => [
                    'siswa.read', 'siswa.update',
                    'kelas.read', 'kelas.update',
                    'presensi.create', 'presensi.read', 'presensi.update',
                    'kredit_poin.create', 'kredit_poin.read', 'kredit_poin.update',
                    'konseling.refer',
                    'dashboard.wali_kelas'
                ]
            ],
            [
                'name' => 'bk',
                'description' => 'Konselor BK dengan akses ke sistem konseling',
                'level' => 3,
                'permissions' => [
                    'siswa.read',
                    'konseling.create', 'konseling.read', 'konseling.update', 'konseling.delete',
                    'home_visit.create', 'home_visit.read', 'home_visit.update',
                    'kredit_poin.read',
                    'dashboard.bk'
                ]
            ],
            [
                'name' => 'osis',
                'description' => 'Pembina OSIS dengan akses ke kegiatan OSIS',
                'level' => 4,
                'permissions' => [
                    'siswa.read',
                    'osis.create', 'osis.read', 'osis.update', 'osis.delete',
                    'kegiatan.create', 'kegiatan.read', 'kegiatan.update',
                    'kredit_poin.create', 'kredit_poin.read',
                    'dashboard.osis'
                ]
            ],
            [
                'name' => 'ekstrakurikuler',
                'description' => 'Pembina ekstrakurikuler',
                'level' => 4,
                'permissions' => [
                    'siswa.read',
                    'ekstrakurikuler.create', 'ekstrakurikuler.read', 'ekstrakurikuler.update', 'ekstrakurikuler.delete',
                    'pendaftaran.read', 'pendaftaran.update',
                    'prestasi.create', 'prestasi.read', 'prestasi.update',
                    'kredit_poin.create', 'kredit_poin.read',
                    'dashboard.ekstrakurikuler'
                ]
            ],
            [
                'name' => 'orang_tua',
                'description' => 'Orang tua siswa dengan akses terbatas',
                'level' => 6,
                'permissions' => [
                    'anak.read',
                    'presensi.read',
                    'kredit_poin.read',
                    'konseling.schedule',
                    'dashboard.orang_tua'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}