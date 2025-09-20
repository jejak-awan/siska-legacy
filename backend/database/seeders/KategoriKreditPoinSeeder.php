<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriKreditPoin;

class KategoriKreditPoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriKreditPoin = [
            // Kredit Poin Positif
            [
                'nama' => 'Kehadiran Sempurna',
                'jenis' => 'positif',
                'nilai_default' => 5,
                'deskripsi' => 'Hadir tepat waktu tanpa terlambat selama 1 bulan',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Prestasi Akademik',
                'jenis' => 'positif',
                'nilai_default' => 10,
                'deskripsi' => 'Mendapat nilai terbaik di kelas atau mata pelajaran',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Prestasi Non-Akademik',
                'jenis' => 'positif',
                'nilai_default' => 15,
                'deskripsi' => 'Mendapat prestasi di bidang olahraga, seni, atau kegiatan lainnya',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kepemimpinan',
                'jenis' => 'positif',
                'nilai_default' => 8,
                'deskripsi' => 'Menjadi ketua kelas, OSIS, atau organisasi lainnya',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kegiatan Sosial',
                'jenis' => 'positif',
                'nilai_default' => 6,
                'deskripsi' => 'Berpartisipasi dalam kegiatan sosial atau bakti sosial',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kebersihan Kelas',
                'jenis' => 'positif',
                'nilai_default' => 3,
                'deskripsi' => 'Menjaga kebersihan kelas dan lingkungan sekolah',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kedisiplinan',
                'jenis' => 'positif',
                'nilai_default' => 4,
                'deskripsi' => 'Menunjukkan kedisiplinan dalam berpakaian dan perilaku',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Membantu Teman',
                'jenis' => 'positif',
                'nilai_default' => 5,
                'deskripsi' => 'Membantu teman yang kesulitan dalam belajar',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kreativitas',
                'jenis' => 'positif',
                'nilai_default' => 7,
                'deskripsi' => 'Menunjukkan kreativitas dalam tugas atau proyek',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Kerjasama Tim',
                'jenis' => 'positif',
                'nilai_default' => 6,
                'deskripsi' => 'Menunjukkan kerjasama yang baik dalam tim',
                'is_aktif' => true,
            ],

            // Kredit Poin Negatif
            [
                'nama' => 'Terlambat',
                'jenis' => 'negatif',
                'nilai_default' => -2,
                'deskripsi' => 'Terlambat masuk sekolah',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Tidak Hadir',
                'jenis' => 'negatif',
                'nilai_default' => -5,
                'deskripsi' => 'Tidak hadir tanpa keterangan yang jelas',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Melanggar Tata Tertib',
                'jenis' => 'negatif',
                'nilai_default' => -3,
                'deskripsi' => 'Melanggar tata tertib sekolah',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Tidak Menggunakan Seragam',
                'jenis' => 'negatif',
                'nilai_default' => -2,
                'deskripsi' => 'Tidak menggunakan seragam sesuai ketentuan',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Membuat Keributan',
                'jenis' => 'negatif',
                'nilai_default' => -4,
                'deskripsi' => 'Membuat keributan di kelas atau lingkungan sekolah',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Tidak Mengerjakan Tugas',
                'jenis' => 'negatif',
                'nilai_default' => -3,
                'deskripsi' => 'Tidak mengerjakan tugas yang diberikan guru',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Mengganggu Proses Belajar',
                'jenis' => 'negatif',
                'nilai_default' => -5,
                'deskripsi' => 'Mengganggu proses belajar mengajar',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Perilaku Tidak Sopan',
                'jenis' => 'negatif',
                'nilai_default' => -4,
                'deskripsi' => 'Berperilaku tidak sopan kepada guru atau teman',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Merusak Fasilitas',
                'jenis' => 'negatif',
                'nilai_default' => -8,
                'deskripsi' => 'Merusak fasilitas sekolah',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Berkelahi',
                'jenis' => 'negatif',
                'nilai_default' => -10,
                'deskripsi' => 'Terlibat dalam perkelahian',
                'is_aktif' => true,
            ],
        ];

        foreach ($kategoriKreditPoin as $kategori) {
            KategoriKreditPoin::updateOrCreate(
                ['nama' => $kategori['nama']],
                $kategori
            );
        }
    }
}