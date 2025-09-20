<?php

namespace App\Services;

use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Models\OrangTua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Service untuk handle import data dengan template Excel
 */
class ImportDataService
{
    /**
     * Template yang tersedia untuk import
     */
    const TEMPLATES = [
        'siswa' => [
            'name' => 'Template Import Data Siswa',
            'file' => 'template_import_siswa.xlsx',
            'model' => Siswa::class
        ],
        'orang_tua' => [
            'name' => 'Template Import Data Orang Tua', 
            'file' => 'template_import_orang_tua.xlsx',
            'model' => OrangTua::class
        ],
        'pegawai' => [
            'name' => 'Template Import Data Pegawai',
            'file' => 'template_import_pegawai.xlsx', 
            'model' => Pegawai::class
        ],
        'kelas' => [
            'name' => 'Template Import Data Kelas',
            'file' => 'template_import_kelas.xlsx',
            'model' => Kelas::class
        ]
    ];

    /**
     * Validasi rules untuk setiap jenis data
     */
    const VALIDATION_RULES = [
        'siswa' => [
            'nis' => 'required|unique:siswa,nis',
            'nisn' => 'required|digits:10|unique:siswa,nisn',
            'nama_lengkap' => 'required|max:100',
            'kelas' => 'required|exists:kelas,nama',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required',
            'alamat_lengkap' => 'required|max:255',
            'nama_ayah' => 'required|max:100',
            'nama_ibu' => 'required|max:100',
            'nomor_hp_ortu' => 'required|regex:/^08[0-9]{8,11}$/',
            'email_ortu' => 'nullable|email'
        ],
        'orang_tua' => [
            'nis_siswa' => 'required|exists:siswa,nis',
            'nama_ayah' => 'required|max:100',
            'nik_ayah' => 'required|digits:16',
            'pekerjaan_ayah' => 'required|max:50',
            'penghasilan_ayah' => 'required|numeric|min:0',
            'nama_ibu' => 'required|max:100',
            'nik_ibu' => 'required|digits:16',
            'pekerjaan_ibu' => 'required|max:50',
            'penghasilan_ibu' => 'required|numeric|min:0'
        ],
        'pegawai' => [
            'nip' => 'required|unique:pegawai,nip',
            'nama_lengkap' => 'required|max:100',
            'jenis_ptk' => 'required|in:Guru,Tenaga Kependidikan',
            'status_kepegawaian' => 'required|in:PNS,PPPK,GTT,PTT',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'pendidikan_terakhir' => 'required|max:100',
            'tanggal_masuk' => 'required|date'
        ],
        'kelas' => [
            'nama_kelas' => 'required|unique:kelas,nama',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|in:IPA,IPS,BHS',
            'wali_kelas_nip' => 'required|exists:pegawai,nip',
            'kapasitas' => 'required|integer|max:40',
            'ruang_kelas' => 'required|max:20'
        ]
    ];

    /**
     * Download template Excel untuk import
     */
    public function downloadTemplate(string $type)
    {
        if (!isset(self::TEMPLATES[$type])) {
            throw new \Exception("Template {$type} tidak ditemukan");
        }

        $template = self::TEMPLATES[$type];
        $filePath = storage_path('app/templates/' . $template['file']);
        
        if (!file_exists($filePath)) {
            $this->generateTemplate($type);
        }

        return response()->download($filePath, $template['file']);
    }

    /**
     * Generate template Excel dengan header dan contoh data
     */
    private function generateTemplate(string $type)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers berdasarkan type
        $headers = $this->getTemplateHeaders($type);
        $exampleData = $this->getExampleData($type);
        
        // Set header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        
        // Set example data
        if ($exampleData) {
            $row = 2;
            foreach ($exampleData as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }
        }
        
        // Save template
        $template = self::TEMPLATES[$type];
        $filePath = storage_path('app/templates/' . $template['file']);
        
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }

    /**
     * Get headers untuk template berdasarkan type
     */
    private function getTemplateHeaders(string $type): array
    {
        return match($type) {
            'siswa' => [
                'NIS', 'NISN', 'Nama Lengkap', 'Kelas', 'Jenis Kelamin',
                'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat Lengkap',
                'Nama Ayah', 'Nama Ibu', 'Nomor HP Orang Tua', 'Email Orang Tua',
                'Anak Ke', 'Jumlah Saudara', 'Golongan Darah', 'Tinggi Badan',
                'Berat Badan', 'Asal Sekolah', 'Nomor HP Siswa', 'Hobi'
            ],
            'orang_tua' => [
                'NIS Siswa', 'Nama Ayah', 'NIK Ayah', 'Pekerjaan Ayah', 
                'Penghasilan Ayah', 'Nama Ibu', 'NIK Ibu', 'Pekerjaan Ibu',
                'Penghasilan Ibu', 'Nama Wali', 'NIK Wali', 'Pekerjaan Wali',
                'Penghasilan Wali', 'Hubungan Dengan Siswa'
            ],
            'pegawai' => [
                'NIP', 'Nama Lengkap', 'Jenis PTK', 'Status Kepegawaian',
                'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 
                'Pendidikan Terakhir', 'Tanggal Masuk', 'Mata Pelajaran'
            ],
            'kelas' => [
                'Nama Kelas', 'Tingkat', 'Jurusan', 'NIP Wali Kelas',
                'Kapasitas', 'Ruang Kelas'
            ]
        };
    }

    /**
     * Get contoh data untuk template
     */
    private function getExampleData(string $type): array
    {
        return match($type) {
            'siswa' => [
                [
                    '2024001', '1234567890', 'Ahmad Budi Santoso', '10-IPA-1', 'L',
                    'Jakarta', '2008-01-15', 'Islam', 'Jl. Merdeka No 123',
                    'Budi Santoso', 'Siti Aminah', '081234567890', 'ortu@email.com',
                    '2', '3', 'A', '165', '55', 'SMP Negeri 1 Jakarta',
                    '081234567891', 'Membaca, Olahraga'
                ]
            ],
            'orang_tua' => [
                [
                    '2024001', 'Budi Santoso, S.E', '3171234567890001', 'Pegawai Swasta',
                    '5000000', 'Siti Aminah, S.Pd', '3171234567890002', 'Guru',
                    '4000000', '', '', '', '', ''
                ]
            ],
            'pegawai' => [
                [
                    '196801011990031001', 'Dr. Ahmad Hidayat, M.Pd', 'Guru', 'PNS',
                    'L', 'Bandung', '1968-01-01', 'S2 Pendidikan Matematika',
                    '1990-03-01', 'Matematika'
                ]
            ],
            'kelas' => [
                [
                    '10-IPA-1', '10', 'IPA', '196801011990031001', '36', 'R.10A'
                ]
            ]
        };
    }

    /**
     * Process import data dari Excel file
     */
    public function processImport(Request $request, string $type)
    {
        if (!isset(self::TEMPLATES[$type])) {
            throw new \Exception("Type import {$type} tidak valid");
        }

        $file = $request->file('import_file');
        if (!$file) {
            throw new \Exception("File tidak ditemukan");
        }

        // Validasi file
        $this->validateFile($file);

        // Load Excel file
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        // Validate dan process data
        return $this->processRows($rows, $type);
    }

    /**
     * Validasi file Excel
     */
    private function validateFile($file)
    {
        if (!$file->isValid()) {
            throw new \Exception("File tidak valid");
        }

        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
            throw new \Exception("Format file harus Excel (.xlsx, .xls) atau CSV");
        }

        if ($file->getSize() > 5242880) { // 5MB
            throw new \Exception("Ukuran file maksimal 5MB");
        }
    }

    /**
     * Process rows data dari Excel
     */
    private function processRows(array $rows, string $type): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'total' => count($rows)
        ];

        $tahunAjaranAktif = TahunAjaran::getAktif();
        if (!$tahunAjaranAktif) {
            throw new \Exception("Tidak ada tahun ajaran aktif. Silakan set tahun ajaran aktif terlebih dahulu.");
        }

        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index dimulai dari 0 dan row 1 adalah header
                
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                try {
                    $this->processRow($row, $type, $tahunAjaranAktif->id, $rowNumber);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => $e->getMessage(),
                        'data' => $row
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $results;
    }

    /**
     * Process single row data
     */
    private function processRow(array $row, string $type, int $tahunAjaranId, int $rowNumber)
    {
        $data = $this->mapRowToData($row, $type);
        
        // Validate data
        $validator = Validator::make($data, self::VALIDATION_RULES[$type]);
        
        if ($validator->fails()) {
            throw new \Exception("Validasi gagal: " . implode(', ', $validator->errors()->all()));
        }

        // Process berdasarkan type
        switch ($type) {
            case 'siswa':
                $this->processSiswaRow($data, $tahunAjaranId);
                break;
            case 'orang_tua':
                $this->processOrangTuaRow($data);
                break;
            case 'pegawai':
                $this->processPegawaiRow($data);
                break;
            case 'kelas':
                $this->processKelasRow($data, $tahunAjaranId);
                break;
        }
    }

    /**
     * Map row data ke array berdasarkan type
     */
    private function mapRowToData(array $row, string $type): array
    {
        $headers = $this->getDataMapping($type);
        $data = [];
        
        foreach ($headers as $index => $field) {
            $data[$field] = $row[$index] ?? null;
        }
        
        return array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * Get mapping field untuk setiap type
     */
    private function getDataMapping(string $type): array
    {
        return match($type) {
            'siswa' => [
                'nis', 'nisn', 'nama_lengkap', 'kelas', 'jenis_kelamin',
                'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat_lengkap',
                'nama_ayah', 'nama_ibu', 'nomor_hp_ortu', 'email_ortu',
                'anak_ke', 'jumlah_saudara', 'golongan_darah', 'tinggi_badan',
                'berat_badan', 'asal_sekolah', 'nomor_hp_siswa', 'hobi'
            ],
            'orang_tua' => [
                'nis_siswa', 'nama_ayah', 'nik_ayah', 'pekerjaan_ayah',
                'penghasilan_ayah', 'nama_ibu', 'nik_ibu', 'pekerjaan_ibu',
                'penghasilan_ibu', 'nama_wali', 'nik_wali', 'pekerjaan_wali',
                'penghasilan_wali', 'hubungan_dengan_siswa'
            ],
            'pegawai' => [
                'nip', 'nama_lengkap', 'jenis_ptk', 'status_kepegawaian',
                'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                'pendidikan_terakhir', 'tanggal_masuk', 'mata_pelajaran_id'
            ],
            'kelas' => [
                'nama_kelas', 'tingkat', 'jurusan', 'wali_kelas_nip',
                'kapasitas', 'ruang_kelas'
            ]
        };
    }

    /**
     * Process data siswa
     */
    private function processSiswaRow(array $data, int $tahunAjaranId)
    {
        // Get kelas ID
        $kelas = Kelas::where('nama', $data['kelas'])->first();
        if (!$kelas) {
            throw new \Exception("Kelas {$data['kelas']} tidak ditemukan");
        }

        // Create or update siswa
        $siswa = Siswa::updateOrCreate(
            ['nis' => $data['nis']],
            [
                'nisn' => $data['nisn'],
                'nama_lengkap' => $data['nama_lengkap'],
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'agama' => $data['agama'],
                'alamat_lengkap' => $data['alamat_lengkap'],
                'anak_ke' => $data['anak_ke'] ?? null,
                'jumlah_saudara' => $data['jumlah_saudara'] ?? null,
                'golongan_darah' => $data['golongan_darah'] ?? null,
                'tinggi_badan' => $data['tinggi_badan'] ?? null,
                'berat_badan' => $data['berat_badan'] ?? null,
                'asal_sekolah' => $data['asal_sekolah'] ?? null,
                'nomor_hp_siswa' => $data['nomor_hp_siswa'] ?? null,
                'hobi' => $data['hobi'] ?? null,
                'status_aktif' => true,
                'imported_at' => now()
            ]
        );

        // Create basic orang tua data
        OrangTua::updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nomor_hp_ayah' => $data['nomor_hp_ortu'],
                'email_ayah' => $data['email_ortu']
            ]
        );
    }

    /**
     * Process data orang tua
     */
    private function processOrangTuaRow(array $data)
    {
        // Get siswa
        $siswa = Siswa::where('nis', $data['nis_siswa'])->first();
        if (!$siswa) {
            throw new \Exception("Siswa dengan NIS {$data['nis_siswa']} tidak ditemukan");
        }

        // Update orang tua data
        OrangTua::updateOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'nama_ayah' => $data['nama_ayah'],
                'nik_ayah' => $data['nik_ayah'],
                'pekerjaan_ayah' => $data['pekerjaan_ayah'],
                'penghasilan_ayah' => $data['penghasilan_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nik_ibu' => $data['nik_ibu'],
                'pekerjaan_ibu' => $data['pekerjaan_ibu'],
                'penghasilan_ibu' => $data['penghasilan_ibu'],
                'nama_wali' => $data['nama_wali'] ?? null,
                'nik_wali' => $data['nik_wali'] ?? null,
                'pekerjaan_wali' => $data['pekerjaan_wali'] ?? null,
                'penghasilan_wali' => $data['penghasilan_wali'] ?? null,
                'hubungan_dengan_siswa' => $data['hubungan_dengan_siswa'] ?? null
            ]
        );
    }

    /**
     * Process data pegawai
     */
    private function processPegawaiRow(array $data)
    {
        Pegawai::updateOrCreate(
            ['nip' => $data['nip']],
            [
                'nama_lengkap' => $data['nama_lengkap'],
                'jenis_ptk' => $data['jenis_ptk'],
                'status_kepegawaian' => $data['status_kepegawaian'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
                'tanggal_masuk' => $data['tanggal_masuk'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'] ?? null,
                'status_aktif' => true
            ]
        );
    }

    /**
     * Process data kelas
     */
    private function processKelasRow(array $data, int $tahunAjaranId)
    {
        // Get wali kelas
        $waliKelas = Pegawai::where('nip', $data['wali_kelas_nip'])->first();
        if (!$waliKelas) {
            throw new \Exception("Pegawai dengan NIP {$data['wali_kelas_nip']} tidak ditemukan");
        }

        Kelas::updateOrCreate(
            ['nama' => $data['nama_kelas'], 'tahun_ajaran_id' => $tahunAjaranId],
            [
                'tingkat_kelas_id' => $data['tingkat'],
                'jurusan' => $data['jurusan'],
                'wali_kelas_id' => $waliKelas->id,
                'kapasitas' => $data['kapasitas'],
                'ruang_kelas' => $data['ruang_kelas']
            ]
        );
    }
}