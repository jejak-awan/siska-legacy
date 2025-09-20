<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OSISKegiatan;
use App\Models\User;

class OSISKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for penanggung jawab
        $penanggungJawab = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'guru', 'osis']);
        })->limit(5)->get();

        if ($penanggungJawab->isEmpty()) {
            $this->command->warn('No users found for penanggung jawab. Skipping OSIS kegiatan seeder.');
            return;
        }

        $osisKegiatanData = [
            [
                'nama_kegiatan' => 'Pekan Olahraga Sekolah',
                'deskripsi' => 'Kompetisi olahraga antar kelas dalam berbagai cabang olahraga',
                'tanggal_mulai' => now()->addDays(7)->toDateString(),
                'tanggal_selesai' => now()->addDays(10)->toDateString(),
                'jam_mulai' => '07:00',
                'jam_selesai' => '15:00',
                'jenis_kegiatan' => 'olahraga',
                'status' => 'persiapan',
                'tempat_kegiatan' => 'Lapangan Sekolah',
                'tujuan_kegiatan' => 'Meningkatkan semangat olahraga dan kebersamaan antar siswa',
                'sasaran_kegiatan' => 'Seluruh siswa kelas X, XI, dan XII',
                'anggaran' => 5000000,
                'sumber_dana' => 'Dana OSIS dan sponsor',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'kelas_x' => 120,
                    'kelas_xi' => 110,
                    'kelas_xii' => 100
                ],
                'panitia' => [
                    'ketua' => 'Ahmad Rizki',
                    'sekretaris' => 'Siti Nurhaliza',
                    'bendahara' => 'Budi Santoso',
                    'koordinator_lapangan' => 'Dedi Kurniawan'
                ],
                'keterangan' => 'Kegiatan tahunan yang rutin dilaksanakan setiap semester',
                'foto_kegiatan' => 'pos_2024.jpg',
                'is_aktif' => true,
                'is_urgent' => false
            ],
            [
                'nama_kegiatan' => 'Festival Seni dan Budaya',
                'deskripsi' => 'Pameran dan pertunjukan seni budaya dari berbagai daerah',
                'tanggal_mulai' => now()->addDays(14)->toDateString(),
                'tanggal_selesai' => now()->addDays(16)->toDateString(),
                'jam_mulai' => '08:00',
                'jam_selesai' => '17:00',
                'jenis_kegiatan' => 'seni',
                'status' => 'perencanaan',
                'tempat_kegiatan' => 'Aula Sekolah dan Halaman',
                'tujuan_kegiatan' => 'Melestarikan dan memperkenalkan seni budaya Indonesia',
                'sasaran_kegiatan' => 'Siswa, guru, dan masyarakat sekitar',
                'anggaran' => 3000000,
                'sumber_dana' => 'Dana OSIS',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'siswa' => 200,
                    'guru' => 30,
                    'masyarakat' => 100
                ],
                'panitia' => [
                    'ketua' => 'Maya Sari',
                    'sekretaris' => 'Eko Prasetyo',
                    'bendahara' => 'Rina Wati',
                    'koordinator_acara' => 'Lina Sari'
                ],
                'keterangan' => 'Menampilkan berbagai kesenian tradisional dan modern',
                'foto_kegiatan' => null,
                'is_aktif' => true,
                'is_urgent' => false
            ],
            [
                'nama_kegiatan' => 'Seminar Motivasi Belajar',
                'deskripsi' => 'Seminar untuk meningkatkan motivasi dan semangat belajar siswa',
                'tanggal_mulai' => now()->addDays(21)->toDateString(),
                'tanggal_selesai' => now()->addDays(21)->toDateString(),
                'jam_mulai' => '09:00',
                'jam_selesai' => '12:00',
                'jenis_kegiatan' => 'akademik',
                'status' => 'perencanaan',
                'tempat_kegiatan' => 'Aula Sekolah',
                'tujuan_kegiatan' => 'Meningkatkan motivasi belajar dan prestasi akademik siswa',
                'sasaran_kegiatan' => 'Siswa kelas XII yang akan menghadapi ujian',
                'anggaran' => 1500000,
                'sumber_dana' => 'Dana OSIS',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'kelas_xii' => 100,
                    'guru' => 20
                ],
                'panitia' => [
                    'ketua' => 'Ahmad Wijaya',
                    'sekretaris' => 'Siti Aminah',
                    'bendahara' => 'Budi Santoso',
                    'koordinator_acara' => 'Dedi Kurniawan'
                ],
                'keterangan' => 'Menghadirkan pembicara dari alumni yang berprestasi',
                'foto_kegiatan' => null,
                'is_aktif' => true,
                'is_urgent' => false
            ],
            [
                'nama_kegiatan' => 'Bakti Sosial ke Panti Asuhan',
                'deskripsi' => 'Kegiatan bakti sosial memberikan bantuan ke panti asuhan',
                'tanggal_mulai' => now()->addDays(28)->toDateString(),
                'tanggal_selesai' => now()->addDays(28)->toDateString(),
                'jam_mulai' => '08:00',
                'jam_selesai' => '15:00',
                'jenis_kegiatan' => 'sosial',
                'status' => 'perencanaan',
                'tempat_kegiatan' => 'Panti Asuhan Al-Hidayah',
                'tujuan_kegiatan' => 'Menumbuhkan rasa kepedulian sosial dan empati',
                'sasaran_kegiatan' => 'Anak-anak panti asuhan dan pengurus panti',
                'anggaran' => 2000000,
                'sumber_dana' => 'Donasi siswa dan guru',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'siswa' => 50,
                    'guru' => 10
                ],
                'panitia' => [
                    'ketua' => 'Maya Sari',
                    'sekretaris' => 'Eko Prasetyo',
                    'bendahara' => 'Rina Wati',
                    'koordinator_logistik' => 'Lina Sari'
                ],
                'keterangan' => 'Mengumpulkan donasi berupa makanan, pakaian, dan buku',
                'foto_kegiatan' => null,
                'is_aktif' => true,
                'is_urgent' => false
            ],
            [
                'nama_kegiatan' => 'Peringatan Hari Kemerdekaan RI',
                'deskripsi' => 'Upacara dan berbagai lomba dalam rangka memperingati HUT RI',
                'tanggal_mulai' => now()->addDays(35)->toDateString(),
                'tanggal_selesai' => now()->addDays(35)->toDateString(),
                'jam_mulai' => '07:00',
                'jam_selesai' => '16:00',
                'jenis_kegiatan' => 'keagamaan',
                'status' => 'perencanaan',
                'tempat_kegiatan' => 'Lapangan Sekolah',
                'tujuan_kegiatan' => 'Memperingati kemerdekaan Indonesia dan menumbuhkan nasionalisme',
                'sasaran_kegiatan' => 'Seluruh warga sekolah',
                'anggaran' => 2500000,
                'sumber_dana' => 'Dana OSIS',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'siswa' => 300,
                    'guru' => 40,
                    'staf' => 20
                ],
                'panitia' => [
                    'ketua' => 'Ahmad Rizki',
                    'sekretaris' => 'Siti Nurhaliza',
                    'bendahara' => 'Budi Santoso',
                    'koordinator_upacara' => 'Dedi Kurniawan'
                ],
                'keterangan' => 'Upacara bendera dan berbagai lomba kemerdekaan',
                'foto_kegiatan' => null,
                'is_aktif' => true,
                'is_urgent' => false
            ],
            [
                'nama_kegiatan' => 'Workshop Kreativitas Digital',
                'deskripsi' => 'Pelatihan membuat konten digital dan media sosial yang positif',
                'tanggal_mulai' => now()->subDays(5)->toDateString(),
                'tanggal_selesai' => now()->subDays(3)->toDateString(),
                'jam_mulai' => '09:00',
                'jam_selesai' => '15:00',
                'jenis_kegiatan' => 'akademik',
                'status' => 'selesai',
                'tempat_kegiatan' => 'Laboratorium Komputer',
                'tujuan_kegiatan' => 'Meningkatkan kemampuan digital dan kreativitas siswa',
                'sasaran_kegiatan' => 'Siswa yang berminat di bidang digital',
                'anggaran' => 1800000,
                'sumber_dana' => 'Dana OSIS dan sponsor',
                'penanggung_jawab' => $penanggungJawab->random()->name ?? 'Admin Sekolah',
                'peserta' => [
                    'siswa' => 30,
                    'guru' => 5
                ],
                'panitia' => [
                    'ketua' => 'Maya Sari',
                    'sekretaris' => 'Eko Prasetyo',
                    'bendahara' => 'Rina Wati',
                    'koordinator_teknis' => 'Lina Sari'
                ],
                'keterangan' => 'Workshop berhasil dilaksanakan dengan antusiasme tinggi',
                'foto_kegiatan' => 'workshop_digital_2024.jpg',
                'is_aktif' => true,
                'is_urgent' => false
            ]
        ];

        foreach ($osisKegiatanData as $data) {
            OSISKegiatan::create($data);
        }
    }
}