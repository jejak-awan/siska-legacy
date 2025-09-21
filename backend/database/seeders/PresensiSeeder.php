<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::where('status_siswa', 'Aktif')->get();
        
        if ($siswa->isEmpty()) {
            $this->command->warn('No active students found. Please run SiswaSeeder first.');
            return;
        }

        $presensiData = [];
        $startDate = Carbon::now()->subDays(30); // Last 30 days
        $endDate = Carbon::now();

        foreach ($siswa as $siswaItem) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Check if attendance record already exists for this user and date
                $existingPresensi = Presensi::where('user_id', $siswaItem->user_id)
                    ->where('tanggal', $currentDate->format('Y-m-d'))
                    ->exists();

                if ($existingPresensi) {
                    $currentDate->addDay();
                    continue;
                }

                $status = $this->generateAttendanceStatus();
                $jamMasuk = null;
                $jamKeluar = null;
                $keterangan = null;

                if ($status === 'hadir') {
                    $jamMasuk = $currentDate->copy()->setTime(7, rand(0, 30), 0);
                    $jamKeluar = $currentDate->copy()->setTime(12, rand(30, 59), 0);
                } elseif ($status === 'izin') {
                    $keterangan = 'Izin sakit';
                } elseif ($status === 'sakit') {
                    $keterangan = 'Sakit';
                } elseif ($status === 'alpha') {
                    $keterangan = 'Tanpa keterangan';
                }

                $presensiData[] = [
                    'user_id' => $siswaItem->user_id,
                    'tanggal' => $currentDate->format('Y-m-d'),
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamKeluar,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $currentDate->addDay();
            }
        }

        // Insert in batches
        $chunks = array_chunk($presensiData, 100);
        foreach ($chunks as $chunk) {
            Presensi::insert($chunk);
        }

        $this->command->info('Presensi seeder completed successfully! Generated ' . count($presensiData) . ' attendance records.');
    }

    /**
     * Generate realistic attendance status
     */
    private function generateAttendanceStatus()
    {
        $rand = rand(1, 100);
        
        if ($rand <= 85) {
            return 'hadir'; // 85% attendance rate
        } elseif ($rand <= 90) {
            return 'izin'; // 5% permission
        } elseif ($rand <= 95) {
            return 'sakit'; // 5% sick
        } else {
            return 'alpha'; // 5% absent
        }
    }
}
