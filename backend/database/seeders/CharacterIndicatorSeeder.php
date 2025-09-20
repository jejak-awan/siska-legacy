<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get dimension IDs
        $dimensions = DB::table('character_dimensions')->get()->keyBy('nama');
        
        $indicators = [
            // Spiritual & Religius
            [
                'dimension_id' => $dimensions['Spiritual & Religius']->id,
                'nama' => 'Konsistensi Ibadah',
                'deskripsi' => 'Menjalankan ibadah sesuai agama yang dianut dengan konsisten',
                'criteria' => json_encode([
                    '1' => 'Tidak pernah menjalankan ibadah',
                    '2' => 'Jarang menjalankan ibadah',
                    '3' => 'Sering menjalankan ibadah',
                    '4' => 'Selalu menjalankan ibadah dengan konsisten'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Spiritual & Religius']->id,
                'nama' => 'Toleransi Beragama',
                'deskripsi' => 'Menghormati perbedaan agama dan kepercayaan',
                'criteria' => json_encode([
                    '1' => 'Tidak menghormati perbedaan agama',
                    '2' => 'Kurang menghormati perbedaan agama',
                    '3' => 'Menghormati perbedaan agama',
                    '4' => 'Sangat menghormati dan mendukung toleransi beragama'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Sosial & Kebangsaan
            [
                'dimension_id' => $dimensions['Sosial & Kebangsaan']->id,
                'nama' => 'Interaksi Sosial',
                'deskripsi' => 'Berinteraksi dengan baik dengan teman sebaya dan lingkungan',
                'criteria' => json_encode([
                    '1' => 'Tidak dapat berinteraksi dengan baik',
                    '2' => 'Kurang dapat berinteraksi dengan baik',
                    '3' => 'Dapat berinteraksi dengan baik',
                    '4' => 'Sangat baik dalam berinteraksi sosial'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Sosial & Kebangsaan']->id,
                'nama' => 'Rasa Kebangsaan',
                'deskripsi' => 'Menunjukkan rasa cinta tanah air dan kebangsaan',
                'criteria' => json_encode([
                    '1' => 'Tidak menunjukkan rasa kebangsaan',
                    '2' => 'Kurang menunjukkan rasa kebangsaan',
                    '3' => 'Menunjukkan rasa kebangsaan',
                    '4' => 'Sangat menunjukkan rasa kebangsaan yang kuat'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Gotong Royong
            [
                'dimension_id' => $dimensions['Gotong Royong']->id,
                'nama' => 'Kerja Sama',
                'deskripsi' => 'Bekerja sama dalam kelompok dan tim',
                'criteria' => json_encode([
                    '1' => 'Tidak dapat bekerja sama',
                    '2' => 'Kurang dapat bekerja sama',
                    '3' => 'Dapat bekerja sama dengan baik',
                    '4' => 'Sangat baik dalam bekerja sama'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Gotong Royong']->id,
                'nama' => 'Kepedulian Sosial',
                'deskripsi' => 'Membantu dan peduli terhadap sesama',
                'criteria' => json_encode([
                    '1' => 'Tidak peduli terhadap sesama',
                    '2' => 'Kurang peduli terhadap sesama',
                    '3' => 'Peduli terhadap sesama',
                    '4' => 'Sangat peduli dan aktif membantu sesama'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Mandiri
            [
                'dimension_id' => $dimensions['Mandiri']->id,
                'nama' => 'Kemandirian',
                'deskripsi' => 'Menyelesaikan tugas tanpa bantuan berlebihan',
                'criteria' => json_encode([
                    '1' => 'Selalu membutuhkan bantuan',
                    '2' => 'Sering membutuhkan bantuan',
                    '3' => 'Kadang membutuhkan bantuan',
                    '4' => 'Selalu mandiri dalam menyelesaikan tugas'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Mandiri']->id,
                'nama' => 'Tanggung Jawab',
                'deskripsi' => 'Bertanggung jawab atas tindakan dan keputusan',
                'criteria' => json_encode([
                    '1' => 'Tidak bertanggung jawab',
                    '2' => 'Kurang bertanggung jawab',
                    '3' => 'Bertanggung jawab',
                    '4' => 'Sangat bertanggung jawab'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Bernalar Kritis
            [
                'dimension_id' => $dimensions['Bernalar Kritis']->id,
                'nama' => 'Analisis Kritis',
                'deskripsi' => 'Menganalisis informasi dengan kritis',
                'criteria' => json_encode([
                    '1' => 'Tidak dapat menganalisis informasi',
                    '2' => 'Kurang dapat menganalisis informasi',
                    '3' => 'Dapat menganalisis informasi',
                    '4' => 'Sangat baik dalam menganalisis informasi'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Bernalar Kritis']->id,
                'nama' => 'Pertanyaan Kritis',
                'deskripsi' => 'Mengajukan pertanyaan yang relevan dan mendalam',
                'criteria' => json_encode([
                    '1' => 'Tidak pernah mengajukan pertanyaan',
                    '2' => 'Jarang mengajukan pertanyaan',
                    '3' => 'Sering mengajukan pertanyaan',
                    '4' => 'Selalu mengajukan pertanyaan yang relevan dan mendalam'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Kreatif
            [
                'dimension_id' => $dimensions['Kreatif']->id,
                'nama' => 'Ide Kreatif',
                'deskripsi' => 'Menghasilkan ide-ide kreatif dan inovatif',
                'criteria' => json_encode([
                    '1' => 'Tidak dapat menghasilkan ide kreatif',
                    '2' => 'Kurang dapat menghasilkan ide kreatif',
                    '3' => 'Dapat menghasilkan ide kreatif',
                    '4' => 'Sangat kreatif dalam menghasilkan ide'
                ]),
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dimension_id' => $dimensions['Kreatif']->id,
                'nama' => 'Ekspresi Kreatif',
                'deskripsi' => 'Mengekspresikan diri dengan kreatif',
                'criteria' => json_encode([
                    '1' => 'Tidak dapat mengekspresikan diri',
                    '2' => 'Kurang dapat mengekspresikan diri',
                    '3' => 'Dapat mengekspresikan diri',
                    '4' => 'Sangat kreatif dalam mengekspresikan diri'
                ]),
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('character_indicators')->insert($indicators);
    }
}
