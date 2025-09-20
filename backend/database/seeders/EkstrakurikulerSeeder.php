<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ekstrakurikuler;
use App\Models\User;

class EkstrakurikulerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for pembina and ketua
        $pembina = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['guru', 'ekstrakurikuler']);
        })->limit(8)->get();

        $ketua = User::whereHas('roles', function($query) {
            $query->where('name', 'siswa');
        })->limit(8)->get();

        if ($pembina->isEmpty() || $ketua->isEmpty()) {
            $this->command->warn('No pembina or ketua found. Skipping ekstrakurikuler seeder.');
            return;
        }

        $ekstrakurikulerData = [
            [
                'nama_ekstrakurikuler' => 'Futsal',
                'deskripsi' => 'Ekstrakurikuler futsal untuk mengembangkan kemampuan bermain futsal dan kerja sama tim',
                'jenis' => 'olahraga',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Senin dan Kamis, 15:00 - 17:00',
                'tempat_latihan' => 'Lapangan Futsal Sekolah',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat dan bakat dalam futsal',
                'kuota_anggota' => 20,
                'biaya_pendaftaran' => 50000,
                'fasilitas' => 'Lapangan futsal, bola, kostum tim',
                'prestasi' => 'Juara 2 Turnamen Futsal Antar Sekolah 2023',
                'foto_ekstrakurikuler' => 'futsal_team.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Paduan Suara',
                'deskripsi' => 'Ekstrakurikuler paduan suara untuk mengembangkan kemampuan vokal dan harmonisasi',
                'jenis' => 'seni',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Selasa dan Jumat, 14:00 - 16:00',
                'tempat_latihan' => 'Aula Sekolah',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki suara yang bagus dan minat bernyanyi',
                'kuota_anggota' => 25,
                'biaya_pendaftaran' => 30000,
                'fasilitas' => 'Piano, mikrofon, partitur lagu',
                'prestasi' => 'Juara 1 Festival Paduan Suara Tingkat Kota 2023',
                'foto_ekstrakurikuler' => 'paduan_suara.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Robotik',
                'deskripsi' => 'Ekstrakurikuler robotik untuk mengembangkan kemampuan programming dan engineering',
                'jenis' => 'akademik',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Rabu dan Sabtu, 13:00 - 15:00',
                'tempat_latihan' => 'Laboratorium Komputer',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat di bidang teknologi dan programming',
                'kuota_anggota' => 15,
                'biaya_pendaftaran' => 100000,
                'fasilitas' => 'Kit robot, laptop, software programming',
                'prestasi' => 'Juara 3 Kompetisi Robotik Nasional 2023',
                'foto_ekstrakurikuler' => 'robotik_team.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Pramuka',
                'deskripsi' => 'Ekstrakurikuler pramuka untuk mengembangkan karakter dan kepemimpinan',
                'jenis' => 'sosial',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Sabtu, 07:00 - 12:00',
                'tempat_latihan' => 'Lapangan Sekolah dan Alam Terbuka',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat di bidang kepramukaan',
                'kuota_anggota' => 40,
                'biaya_pendaftaran' => 25000,
                'fasilitas' => 'Tenda, kompas, tali, peralatan camping',
                'prestasi' => 'Juara 1 Jambore Ranting 2023',
                'foto_ekstrakurikuler' => 'pramuka_team.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Tari Tradisional',
                'deskripsi' => 'Ekstrakurikuler tari tradisional untuk melestarikan budaya Indonesia',
                'jenis' => 'seni',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Senin dan Kamis, 15:30 - 17:30',
                'tempat_latihan' => 'Ruang Tari',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat dan bakat menari',
                'kuota_anggota' => 20,
                'biaya_pendaftaran' => 40000,
                'fasilitas' => 'Kostum tari, musik tradisional, cermin',
                'prestasi' => 'Juara 2 Festival Tari Tradisional Tingkat Provinsi 2023',
                'foto_ekstrakurikuler' => 'tari_tradisional.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Basketball',
                'deskripsi' => 'Ekstrakurikuler basketball untuk mengembangkan kemampuan bermain basket',
                'jenis' => 'olahraga',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Selasa dan Jumat, 15:00 - 17:00',
                'tempat_latihan' => 'Lapangan Basketball',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat dan bakat dalam basketball',
                'kuota_anggota' => 18,
                'biaya_pendaftaran' => 60000,
                'fasilitas' => 'Lapangan basketball, bola, kostum tim',
                'prestasi' => 'Juara 1 Turnamen Basketball Antar Sekolah 2023',
                'foto_ekstrakurikuler' => 'basketball_team.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'English Club',
                'deskripsi' => 'Ekstrakurikuler English Club untuk meningkatkan kemampuan berbahasa Inggris',
                'jenis' => 'akademik',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Rabu dan Sabtu, 14:00 - 16:00',
                'tempat_latihan' => 'Ruang Bahasa',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat meningkatkan kemampuan bahasa Inggris',
                'kuota_anggota' => 30,
                'biaya_pendaftaran' => 35000,
                'fasilitas' => 'Buku bahasa Inggris, audio visual, native speaker',
                'prestasi' => 'Juara 2 English Debate Competition 2023',
                'foto_ekstrakurikuler' => 'english_club.jpg',
                'is_aktif' => true
            ],
            [
                'nama_ekstrakurikuler' => 'Karate',
                'deskripsi' => 'Ekstrakurikuler karate untuk mengembangkan kemampuan bela diri dan disiplin',
                'jenis' => 'olahraga',
                'status' => 'aktif',
                'pembina_id' => $pembina->random()->id,
                'ketua_id' => $ketua->random()->id,
                'jadwal_latihan' => 'Senin dan Kamis, 16:00 - 18:00',
                'tempat_latihan' => 'Dojo Sekolah',
                'persyaratan_anggota' => 'Siswa kelas X-XII, memiliki minat di bidang bela diri',
                'kuota_anggota' => 25,
                'biaya_pendaftaran' => 75000,
                'fasilitas' => 'Dojo, seragam karate, peralatan latihan',
                'prestasi' => 'Juara 1 Kejuaraan Karate Tingkat Kota 2023',
                'foto_ekstrakurikuler' => 'karate_team.jpg',
                'is_aktif' => true
            ]
        ];

        foreach ($ekstrakurikulerData as $data) {
            Ekstrakurikuler::create($data);
        }
    }
}