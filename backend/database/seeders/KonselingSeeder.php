<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Models\User;

class KonselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some students and counselors
        $siswa = Siswa::limit(10)->get();
        $konselor = User::whereHas('roles', function($query) {
            $query->where('name', 'bk');
        })->limit(3)->get();

        if ($siswa->isEmpty() || $konselor->isEmpty()) {
            $this->command->warn('No students or counselors found. Skipping konseling seeder.');
            return;
        }

        $konselingData = [
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_konseling' => now()->subDays(5)->toDateString(),
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:00',
                'jenis_konseling' => 'individual',
                'status' => 'selesai',
                'masalah' => 'Kesulitan dalam memahami mata pelajaran matematika',
                'tujuan_konseling' => 'Membantu siswa memahami konsep dasar matematika',
                'intervensi' => 'Memberikan metode belajar yang sesuai dengan gaya belajar siswa',
                'hasil_konseling' => 'Siswa menunjukkan peningkatan dalam pemahaman matematika',
                'tindak_lanjut' => 'Monitoring progress belajar selama 2 minggu ke depan',
                'catatan_konselor' => 'Siswa sangat kooperatif dan menunjukkan kemauan untuk belajar',
                'tempat_konseling' => 'Ruang BK',
                'is_urgent' => false,
                'is_confidential' => true,
                'data_tambahan' => [
                    'metode_konseling' => 'cognitive_behavioral',
                    'durasi_efektif' => 45,
                    'follow_up_schedule' => '2024-10-15'
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_konseling' => now()->subDays(3)->toDateString(),
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:00',
                'jenis_konseling' => 'kelompok',
                'status' => 'berlangsung',
                'masalah' => 'Konflik antar teman sekelas',
                'tujuan_konseling' => 'Menyelesaikan konflik dan meningkatkan kerjasama',
                'intervensi' => 'Mediasi kelompok dan komunikasi efektif',
                'hasil_konseling' => 'Sedang dalam proses mediasi',
                'tindak_lanjut' => 'Sesi lanjutan minggu depan',
                'catatan_konselor' => 'Perlu pendekatan yang lebih intensif',
                'tempat_konseling' => 'Ruang BK',
                'is_urgent' => true,
                'is_confidential' => false,
                'data_tambahan' => [
                    'jumlah_peserta' => 4,
                    'metode_konseling' => 'group_therapy',
                    'durasi_efektif' => 50
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_konseling' => now()->subDays(1)->toDateString(),
                'jam_mulai' => '14:00',
                'jam_selesai' => '15:00',
                'jenis_konseling' => 'keluarga',
                'status' => 'terjadwal',
                'masalah' => 'Masalah komunikasi dengan orang tua',
                'tujuan_konseling' => 'Meningkatkan komunikasi yang efektif dalam keluarga',
                'intervensi' => 'Family therapy dan komunikasi terbuka',
                'hasil_konseling' => null,
                'tindak_lanjut' => 'Sesi konseling keluarga',
                'catatan_konselor' => 'Orang tua sudah diundang untuk sesi konseling',
                'tempat_konseling' => 'Ruang BK',
                'is_urgent' => false,
                'is_confidential' => true,
                'data_tambahan' => [
                    'keluarga_terlibat' => ['ayah', 'ibu'],
                    'metode_konseling' => 'family_therapy',
                    'durasi_efektif' => 60
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_konseling' => now()->addDays(2)->toDateString(),
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
                'jenis_konseling' => 'individual',
                'status' => 'terjadwal',
                'masalah' => 'Kecemasan menghadapi ujian',
                'tujuan_konseling' => 'Mengurangi kecemasan dan meningkatkan kepercayaan diri',
                'intervensi' => 'Relaxation techniques dan positive thinking',
                'hasil_konseling' => null,
                'tindak_lanjut' => 'Pemberian teknik relaksasi',
                'catatan_konselor' => 'Siswa menunjukkan gejala anxiety yang perlu ditangani',
                'tempat_konseling' => 'Ruang BK',
                'is_urgent' => false,
                'is_confidential' => true,
                'data_tambahan' => [
                    'metode_konseling' => 'cognitive_behavioral',
                    'durasi_efektif' => 45,
                    'follow_up_schedule' => '2024-10-20'
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_konseling' => now()->subDays(7)->toDateString(),
                'jam_mulai' => '11:00',
                'jam_selesai' => '12:00',
                'jenis_konseling' => 'krisis',
                'status' => 'selesai',
                'masalah' => 'Krisis emosional akibat masalah keluarga',
                'tujuan_konseling' => 'Memberikan dukungan emosional dan coping mechanism',
                'intervensi' => 'Crisis intervention dan emotional support',
                'hasil_konseling' => 'Siswa sudah lebih stabil secara emosional',
                'tindak_lanjut' => 'Monitoring berkala dan dukungan berkelanjutan',
                'catatan_konselor' => 'Perlu koordinasi dengan keluarga dan guru',
                'tempat_konseling' => 'Ruang BK',
                'is_urgent' => true,
                'is_confidential' => true,
                'data_tambahan' => [
                    'metode_konseling' => 'crisis_intervention',
                    'durasi_efektif' => 60,
                    'follow_up_schedule' => '2024-10-18',
                    'emergency_contact' => true
                ]
            ]
        ];

        foreach ($konselingData as $data) {
            Konseling::create($data);
        }
    }
}