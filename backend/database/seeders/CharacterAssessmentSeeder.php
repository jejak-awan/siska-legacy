<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CharacterAssessment;
use App\Models\CharacterDimension;
use App\Models\Siswa;
use Carbon\Carbon;

class CharacterAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::where('status_siswa', 'Aktif')->get();
        $dimensions = CharacterDimension::all();
        
        if ($siswa->isEmpty()) {
            $this->command->warn('No active students found. Please run SiswaSeeder first.');
            return;
        }

        if ($dimensions->isEmpty()) {
            $this->command->warn('No character dimensions found. Please run CharacterDimensionSeeder first.');
            return;
        }

        $assessmentData = [];
        $startDate = Carbon::now()->subMonths(3); // Last 3 months
        $endDate = Carbon::now();

        foreach ($siswa as $siswaItem) {
            // Generate 1-2 assessments per student
            $jumlahAssessment = rand(1, 2);
            
            for ($i = 0; $i < $jumlahAssessment; $i++) {
                $tanggal = Carbon::createFromFormat('Y-m-d', $startDate->format('Y-m-d'))
                    ->addDays(rand(0, $startDate->diffInDays($endDate)));

                $scores = [];
                $totalScore = 0;
                $scoreCount = 0;

                // Generate scores for each dimension (simplified without indicators)
                foreach ($dimensions as $dimension) {
                    $score = rand(3, 5); // Score between 3-5
                    $scores[$dimension->id] = $score;
                    $totalScore += $score;
                    $scoreCount++;
                }

                $averageScore = $scoreCount > 0 ? $totalScore / $scoreCount : 0;

                $assessmentData[] = [
                    'siswa_id' => $siswaItem->id,
                    'assessor_id' => 1, // Admin user
                    'periode' => $this->getPeriode($tanggal),
                    'semester' => $this->getSemester($tanggal),
                    'tanggal_penilaian' => $tanggal->format('Y-m-d'),
                    'scores' => json_encode($scores),
                    'total_score' => round($averageScore, 2),
                    'comments' => $this->generateComments($averageScore),
                    'evidence' => json_encode(['bukti_1' => 'Observasi langsung', 'bukti_2' => 'Laporan guru']),
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => $tanggal->addDays(rand(1, 7)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in batches
        $chunks = array_chunk($assessmentData, 100);
        foreach ($chunks as $chunk) {
            CharacterAssessment::insert($chunk);
        }

        $this->command->info('Character Assessment seeder completed successfully! Generated ' . count($assessmentData) . ' assessment records.');
    }

    /**
     * Get period based on date
     */
    private function getPeriode($tanggal)
    {
        $month = $tanggal->month;
        
        if ($month >= 1 && $month <= 3) {
            return 'Januari-Maret';
        } elseif ($month >= 4 && $month <= 6) {
            return 'April-Juni';
        } elseif ($month >= 7 && $month <= 9) {
            return 'Juli-September';
        } else {
            return 'Oktober-Desember';
        }
    }

    /**
     * Get semester based on date
     */
    private function getSemester($tanggal)
    {
        $month = $tanggal->month;
        
        if ($month >= 1 && $month <= 6) {
            return '2';
        } else {
            return '1';
        }
    }

    /**
     * Generate comments based on score
     */
    private function generateComments($score)
    {
        if ($score >= 4.5) {
            return 'Siswa menunjukkan karakter yang sangat baik dan konsisten. Perlu dipertahankan dan dikembangkan lebih lanjut.';
        } elseif ($score >= 4.0) {
            return 'Siswa menunjukkan karakter yang baik. Ada beberapa aspek yang perlu ditingkatkan.';
        } elseif ($score >= 3.5) {
            return 'Siswa menunjukkan karakter yang cukup baik. Perlu bimbingan lebih intensif untuk peningkatan.';
        } else {
            return 'Siswa perlu bimbingan khusus untuk pengembangan karakter. Perlu perhatian khusus dari guru dan orang tua.';
        }
    }
}
