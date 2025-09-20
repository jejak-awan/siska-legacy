<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeVisit;
use App\Models\Siswa;
use App\Models\User;

class HomeVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some students and counselors
        $siswa = Siswa::limit(8)->get();
        $konselor = User::whereHas('roles', function($query) {
            $query->where('name', 'bk');
        })->limit(2)->get();

        if ($siswa->isEmpty() || $konselor->isEmpty()) {
            $this->command->warn('No students or counselors found. Skipping home visit seeder.');
            return;
        }

        $homeVisitData = [
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_kunjungan' => now()->subDays(10)->toDateString(),
                'jam_mulai' => '14:00',
                'jam_selesai' => '15:30',
                'status' => 'selesai',
                'tujuan_kunjungan' => 'Mendiskusikan masalah kehadiran siswa yang sering terlambat',
                'hasil_kunjungan' => 'Orang tua menyadari pentingnya disiplin waktu dan akan lebih memperhatikan jadwal anak',
                'catatan_kunjungan' => 'Keluarga sangat kooperatif dan menunjukkan komitmen untuk perubahan',
                'tindak_lanjut' => 'Monitoring kehadiran siswa selama 2 minggu ke depan',
                'alamat_kunjungan' => 'Jl. Merdeka No. 123, Kelurahan Sukajadi, Kecamatan Bandung Wetan',
                'koordinat_lat' => '-6.9175',
                'koordinat_lng' => '107.6191',
                'foto_kunjungan' => 'home_visit_1.jpg',
                'is_urgent' => false,
                'is_confidential' => true,
                'data_keluarga' => [
                    'ayah' => [
                        'nama' => 'Budi Santoso',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'SMA',
                        'telepon' => '081234567890'
                    ],
                    'ibu' => [
                        'nama' => 'Siti Aminah',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                        'pendidikan' => 'SMP',
                        'telepon' => '081234567891'
                    ]
                ],
                'data_lingkungan' => [
                    'kondisi_rumah' => 'Layak huni',
                    'fasilitas_belajar' => 'Memadai',
                    'lingkungan_sosial' => 'Baik',
                    'akses_transportasi' => 'Mudah'
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_kunjungan' => now()->subDays(5)->toDateString(),
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:30',
                'status' => 'selesai',
                'tujuan_kunjungan' => 'Mendiskusikan masalah akademik dan motivasi belajar',
                'hasil_kunjungan' => 'Orang tua akan lebih memperhatikan proses belajar anak di rumah',
                'catatan_kunjungan' => 'Keluarga memiliki keterbatasan ekonomi namun sangat peduli dengan pendidikan',
                'tindak_lanjut' => 'Koordinasi dengan guru untuk memberikan perhatian khusus',
                'alamat_kunjungan' => 'Jl. Pahlawan No. 45, Kelurahan Cicaheum, Kecamatan Kiaracondong',
                'koordinat_lat' => '-6.9147',
                'koordinat_lng' => '107.6098',
                'foto_kunjungan' => 'home_visit_2.jpg',
                'is_urgent' => false,
                'is_confidential' => true,
                'data_keluarga' => [
                    'ayah' => [
                        'nama' => 'Ahmad Wijaya',
                        'pekerjaan' => 'Buruh',
                        'pendidikan' => 'SD',
                        'telepon' => '081234567892'
                    ],
                    'ibu' => [
                        'nama' => 'Rina Sari',
                        'pekerjaan' => 'Penjual',
                        'pendidikan' => 'SMP',
                        'telepon' => '081234567893'
                    ]
                ],
                'data_lingkungan' => [
                    'kondisi_rumah' => 'Sederhana',
                    'fasilitas_belajar' => 'Terbatas',
                    'lingkungan_sosial' => 'Baik',
                    'akses_transportasi' => 'Cukup'
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_kunjungan' => now()->addDays(3)->toDateString(),
                'jam_mulai' => '15:00',
                'jam_selesai' => '16:30',
                'status' => 'terjadwal',
                'tujuan_kunjungan' => 'Mendiskusikan masalah perilaku dan disiplin siswa',
                'hasil_kunjungan' => null,
                'catatan_kunjungan' => 'Siswa menunjukkan perubahan perilaku yang perlu ditangani',
                'tindak_lanjut' => 'Sesi konseling lanjutan',
                'alamat_kunjungan' => 'Jl. Gatot Subroto No. 78, Kelurahan Dago, Kecamatan Coblong',
                'koordinat_lat' => '-6.9039',
                'koordinat_lng' => '107.6186',
                'foto_kunjungan' => null,
                'is_urgent' => true,
                'is_confidential' => true,
                'data_keluarga' => [
                    'ayah' => [
                        'nama' => 'Dedi Kurniawan',
                        'pekerjaan' => 'PNS',
                        'pendidikan' => 'S1',
                        'telepon' => '081234567894'
                    ],
                    'ibu' => [
                        'nama' => 'Lina Wati',
                        'pekerjaan' => 'Guru',
                        'pendidikan' => 'S1',
                        'telepon' => '081234567895'
                    ]
                ],
                'data_lingkungan' => [
                    'kondisi_rumah' => 'Baik',
                    'fasilitas_belajar' => 'Lengkap',
                    'lingkungan_sosial' => 'Sangat baik',
                    'akses_transportasi' => 'Mudah'
                ]
            ],
            [
                'siswa_id' => $siswa->random()->id,
                'konselor_id' => $konselor->random()->id,
                'tanggal_kunjungan' => now()->subDays(2)->toDateString(),
                'jam_mulai' => '13:00',
                'jam_selesai' => '14:30',
                'status' => 'berlangsung',
                'tujuan_kunjungan' => 'Mendiskusikan masalah keluarga yang mempengaruhi prestasi belajar',
                'hasil_kunjungan' => 'Sedang dalam proses diskusi dengan keluarga',
                'catatan_kunjungan' => 'Keluarga mengalami masalah internal yang perlu penanganan khusus',
                'tindak_lanjut' => 'Koordinasi dengan pihak terkait untuk bantuan',
                'alamat_kunjungan' => 'Jl. Asia Afrika No. 12, Kelurahan Braga, Kecamatan Sumur Bandung',
                'koordinat_lat' => '-6.9214',
                'koordinat_lng' => '107.6091',
                'foto_kunjungan' => 'home_visit_4.jpg',
                'is_urgent' => true,
                'is_confidential' => true,
                'data_keluarga' => [
                    'ayah' => [
                        'nama' => 'Eko Prasetyo',
                        'pekerjaan' => 'Karyawan Swasta',
                        'pendidikan' => 'D3',
                        'telepon' => '081234567896'
                    ],
                    'ibu' => [
                        'nama' => 'Maya Sari',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                        'pendidikan' => 'SMA',
                        'telepon' => '081234567897'
                    ]
                ],
                'data_lingkungan' => [
                    'kondisi_rumah' => 'Layak huni',
                    'fasilitas_belajar' => 'Memadai',
                    'lingkungan_sosial' => 'Baik',
                    'akses_transportasi' => 'Mudah'
                ]
            ]
        ];

        foreach ($homeVisitData as $data) {
            HomeVisit::create($data);
        }
    }
}