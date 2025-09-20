<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::updateOrCreate([
            'username' => 'admin'
        ], [
            'username' => 'admin',
            'email' => 'admin@kesiswaan.id',
            'password' => Hash::make('password123'),
            'role_type' => 'admin',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminUser->roles()->syncWithoutDetaching([
                $adminRole->id => ['assigned_by' => $adminUser->id, 'assigned_at' => now()]
            ]);
        }

        // Create Test Guru User
        $guruUser = User::updateOrCreate([
            'username' => 'guru001'
        ], [
            'username' => 'guru001',
            'email' => 'guru001@kesiswaan.id',
            'password' => Hash::make('password123'),
            'role_type' => 'guru',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Assign guru role
        $guruRole = Role::where('name', 'guru')->first();
        if ($guruRole) {
            $guruUser->roles()->syncWithoutDetaching([
                $guruRole->id => ['assigned_by' => $adminUser->id, 'assigned_at' => now()]
            ]);
        }

        // Create Guru Profile
        Guru::updateOrCreate([
            'user_id' => $guruUser->id
        ], [
            'user_id' => $guruUser->id,
            'nip' => '196801011990031001',
            'nuptk' => '1234567890123456',
            'nama_lengkap' => 'Dr. Ahmad Sutrisno, S.Pd., M.Pd.',
            'nama_panggilan' => 'Pak Ahmad',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1968-01-01',
            'agama' => 'Islam',
            'status_pernikahan' => 'Menikah',
            'alamat_lengkap' => 'Jl. Pendidikan No. 123, Jakarta Selatan',
            'rt_rw' => '001/002',
            'kelurahan' => 'Kebayoran Baru',
            'kecamatan' => 'Kebayoran Baru',
            'kabupaten_kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12110',
            'nomor_hp' => '081234567890',
            'email' => 'guru001@kesiswaan.id',
            'jenis_ptk' => 'Guru Mapel',
            'status_kepegawaian' => 'PNS',
            'golongan' => 'IV/a',
            'jabatan' => 'Guru Matematika',
            'unit_kerja' => 'SMA Negeri 1 Jakarta',
            'tanggal_masuk' => '1990-03-01',
            'masa_kerja' => 34,
            'pendidikan_terakhir' => 'S2',
            'jurusan_pendidikan' => 'Pendidikan Matematika',
            'nama_universitas' => 'Universitas Pendidikan Indonesia',
            'tahun_lulus' => 1995,
            'mata_pelajaran' => 'Matematika',
            'kelas_yang_diajar' => ['X-A', 'X-B', 'XI IPA 1'],
            'is_wali_kelas' => true,
            'kelas_wali' => 'X-A',
            'status_sertifikasi' => 'Tersertifikasi',
            'no_sertifikat_pendidik' => 'SERT123456789',
            'tanggal_sertifikasi' => '2010-06-15',
            'lembaga_sertifikasi' => 'LPTK UNJ',
            'status_aktif' => true,
        ]);

        // Create Test Siswa User
        $siswaUser = User::updateOrCreate([
            'username' => 'siswa001'
        ], [
            'username' => 'siswa001',
            'email' => 'siswa001@kesiswaan.id',
            'password' => Hash::make('password123'),
            'role_type' => 'siswa',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Assign siswa role
        $siswaRole = Role::where('name', 'siswa')->first();
        if ($siswaRole) {
            $siswaUser->roles()->syncWithoutDetaching([
                $siswaRole->id => ['assigned_by' => $adminUser->id, 'assigned_at' => now()]
            ]);
        }

        // Get active tahun ajaran
        $tahunAjaran = TahunAjaran::where('is_aktif', true)->first();

        // Create Siswa Profile
        Siswa::updateOrCreate([
            'user_id' => $siswaUser->id
        ], [
            'user_id' => $siswaUser->id,
            'nisn' => '1234567890',
            'nis' => '2024001001',
            'nama_lengkap' => 'Siti Nurhaliza',
            'nama_panggilan' => 'Siti',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2008-05-15',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'nik' => '3201234567890123',
            'no_kk' => '3201234567890001',
            'no_akta_kelahiran' => 'AK-123456789',
            'anak_ke' => 1,
            'jumlah_saudara' => 2,
            'bahasa_sehari_hari' => 'Bahasa Indonesia',
            'tinggi_badan' => 160.5,
            'berat_badan' => 50.0,
            'golongan_darah' => 'A',
            'alamat_lengkap' => 'Jl. Siswa No. 456, Bandung',
            'rt_rw' => '003/004',
            'kelurahan' => 'Cicendo',
            'kecamatan' => 'Cicendo',
            'kabupaten_kota' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40171',
            'nomor_hp_siswa' => '081987654321',
            'email_siswa' => 'siswa001@kesiswaan.id',
            'kelas' => 'X-A',
            'angkatan' => 2024,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'asal_sekolah' => 'SMP Negeri 1 Bandung',
            'tanggal_masuk' => '2024-07-15',
            'status_siswa' => 'Aktif',
            'jarak_ke_sekolah' => 5.5,
            'transportasi' => 'Angkutan Umum',
            'waktu_tempuh' => 30,
            'penerima_beasiswa' => false,
            'penerima_kip' => false,
            'penerima_pkh' => false,
            'hobi' => ['Membaca', 'Menyanyi', 'Olahraga'],
            'cita_cita' => ['Dokter', 'Peneliti'],
            'prestasi' => [
                [
                    'nama' => 'Juara 1 Olimpiade Matematika Tingkat Kota',
                    'tahun' => 2023,
                    'tingkat' => 'Kota'
                ]
            ],
            'ekstrakurikuler' => ['Pramuka', 'KIR'],
            'status_aktif' => true,
        ]);

        echo "Admin, Guru, dan Siswa test users created successfully!\n";
        echo "Admin - Username: admin, Password: password123\n";
        echo "Guru - Username: guru001, Password: password123\n";
        echo "Siswa - Username: siswa001, Password: password123\n";
    }
}