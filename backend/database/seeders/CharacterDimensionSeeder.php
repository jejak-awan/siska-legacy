<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dimensions = [
            [
                'nama' => 'Spiritual & Religius',
                'deskripsi' => 'Kemampuan menghayati dan mengamalkan ajaran agama yang dianut',
                'indikator' => json_encode([
                    'Menjalankan ibadah sesuai agama yang dianut',
                    'Menghormati perbedaan agama dan kepercayaan',
                    'Menerapkan nilai-nilai spiritual dalam kehidupan sehari-hari',
                    'Berperilaku sesuai dengan ajaran agama'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sosial & Kebangsaan',
                'deskripsi' => 'Kemampuan berinteraksi sosial dan memiliki rasa kebangsaan',
                'indikator' => json_encode([
                    'Berinteraksi dengan baik dengan teman sebaya',
                    'Menghormati guru dan tenaga kependidikan',
                    'Menunjukkan rasa cinta tanah air',
                    'Berpartisipasi dalam kegiatan kemasyarakatan'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Gotong Royong',
                'deskripsi' => 'Kemampuan bekerja sama dan membantu sesama',
                'indikator' => json_encode([
                    'Bekerja sama dalam kelompok',
                    'Membantu teman yang membutuhkan',
                    'Berpartisipasi dalam kegiatan gotong royong',
                    'Menghargai kontribusi orang lain'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Mandiri',
                'deskripsi' => 'Kemampuan mandiri dan bertanggung jawab',
                'indikator' => json_encode([
                    'Menyelesaikan tugas tanpa bantuan berlebihan',
                    'Bertanggung jawab atas tindakan sendiri',
                    'Mengatur waktu dan prioritas dengan baik',
                    'Mengambil keputusan yang tepat'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Bernalar Kritis',
                'deskripsi' => 'Kemampuan berpikir kritis dan analitis',
                'indikator' => json_encode([
                    'Menganalisis informasi dengan kritis',
                    'Mengajukan pertanyaan yang relevan',
                    'Menarik kesimpulan yang logis',
                    'Mengevaluasi argumen dengan objektif'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Kreatif',
                'deskripsi' => 'Kemampuan kreativitas dan inovasi',
                'indikator' => json_encode([
                    'Menghasilkan ide-ide kreatif',
                    'Menggunakan pendekatan yang inovatif',
                    'Mengembangkan solusi yang unik',
                    'Mengekspresikan diri dengan kreatif'
                ]),
                'skala_min' => 1,
                'skala_max' => 4,
                'skala_label' => '1-4',
                'is_active' => true,
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('character_dimensions')->insert($dimensions);
    }
}
