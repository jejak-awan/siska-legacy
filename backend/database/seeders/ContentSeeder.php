<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Public\GeneralPost;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'title' => 'Selamat Datang di SISKA',
                'content' => '<h2>Selamat Datang di Sistem Informasi Kesiswaan (SISKA)</h2><p>SISKA adalah sistem manajemen kesiswaan yang komprehensif untuk membantu pengelolaan data siswa, presensi, kredit poin, dan berbagai aspek kesiswaan lainnya.</p><h3>Fitur Utama:</h3><ul><li>Manajemen Data Siswa</li><li>Sistem Presensi Digital</li><li>Kredit Poin Perilaku</li><li>Penilaian Karakter</li><li>Manajemen Ekstrakurikuler</li><li>Organisasi OSIS</li></ul>',
                'category' => 'informasi',
                'subcategory' => 'umum',
                'status' => 'published',
                'tags' => json_encode(['siska', 'sistem', 'kesiswaan']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => true,
                'is_pinned' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Panduan Penggunaan SISKA',
                'content' => '<h2>Panduan Penggunaan SISKA</h2><p>Berikut adalah panduan lengkap untuk menggunakan sistem SISKA:</p><h3>1. Login dan Autentikasi</h3><p>Gunakan kredensial yang telah diberikan untuk mengakses sistem.</p><h3>2. Dashboard</h3><p>Dashboard memberikan overview data dan statistik penting.</p><h3>3. Manajemen Siswa</h3><p>Kelola data siswa, kelas, dan informasi akademik.</p>',
                'category' => 'panduan',
                'subcategory' => 'penggunaan',
                'status' => 'published',
                'tags' => json_encode(['panduan', 'siska', 'penggunaan']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Pengumuman Jadwal Ujian Semester',
                'content' => '<h2>Pengumuman Jadwal Ujian Semester</h2><p>Diberitahukan kepada seluruh siswa bahwa jadwal ujian semester akan dimulai pada:</p><ul><li><strong>Tanggal:</strong> 15 Januari 2024</li><li><strong>Waktu:</strong> 07.00 - 12.00 WIB</li><li><strong>Tempat:</strong> Ruang kelas masing-masing</li></ul><p>Harap mempersiapkan diri dengan baik dan datang tepat waktu.</p>',
                'category' => 'pengumuman',
                'subcategory' => 'ujian',
                'status' => 'published',
                'tags' => json_encode(['pengumuman', 'ujian', 'semester']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => false,
                'is_pinned' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Kegiatan Ekstrakurikuler Semester Ini',
                'content' => '<h2>Kegiatan Ekstrakurikuler Semester Ini</h2><p>Berikut adalah daftar ekstrakurikuler yang tersedia untuk semester ini:</p><h3>Olahraga:</h3><ul><li>Basket</li><li>Voli</li><li>Futsal</li><li>Karate</li></ul><h3>Seni dan Budaya:</h3><ul><li>Paduan Suara</li><li>Tari Tradisional</li><li>Teater</li><li>Fotografi</li></ul><h3>Keilmuan:</h3><ul><li>Debat Bahasa Inggris</li><li>Matematika Club</li><li>Robotik</li><li>Jurnalistik</li></ul>',
                'category' => 'ekstrakurikuler',
                'subcategory' => 'daftar',
                'status' => 'published',
                'tags' => json_encode(['ekstrakurikuler', 'kegiatan', 'semester']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => false,
                'is_pinned' => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Prestasi Siswa di Kompetisi Nasional',
                'content' => '<h2>Prestasi Siswa di Kompetisi Nasional</h2><p>Kami bangga mengumumkan prestasi siswa-siswi kita di berbagai kompetisi nasional:</p><h3>Lomba Debat Bahasa Inggris</h3><p><strong>Juara 1:</strong> Ahmad Rizki (XI IPA 1)</p><h3>Olimpiade Matematika</h3><p><strong>Juara 2:</strong> Sari Dewi (XI IPA 2)</p><h3>Lomba Basket</h3><p><strong>Juara 3:</strong> Tim Basket Putra</p><p>Selamat kepada semua pemenang! Terus semangat dan berprestasi!</p>',
                'category' => 'prestasi',
                'subcategory' => 'kompetisi',
                'status' => 'published',
                'tags' => json_encode(['prestasi', 'kompetisi', 'nasional']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Kebijakan Kredit Poin Perilaku',
                'content' => '<h2>Kebijakan Kredit Poin Perilaku</h2><p>Sistem kredit poin perilaku bertujuan untuk membentuk karakter positif siswa. Berikut adalah ketentuan yang berlaku:</p><h3>Poin Positif:</h3><ul><li>Membantu teman: +5 poin</li><li>Mengikuti kegiatan sekolah: +10 poin</li><li>Prestasi akademik: +15 poin</li><li>Prestasi non-akademik: +20 poin</li></ul><h3>Poin Negatif:</h3><ul><li>Terlambat: -5 poin</li><li>Berkelahi: -20 poin</li><li>Bolos sekolah: -15 poin</li><li>Melanggar tata tertib: -10 poin</li></ul>',
                'category' => 'kebijakan',
                'subcategory' => 'kredit-poin',
                'status' => 'published',
                'tags' => json_encode(['kebijakan', 'kredit-poin', 'perilaku']),
                'attachments' => json_encode([]),
                'author_role' => 'admin',
                'author_id' => 1,
                'is_featured' => false,
                'is_pinned' => false,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($contents as $contentData) {
            GeneralPost::create($contentData);
        }

        $this->command->info('Content seeder completed successfully!');
    }
}
