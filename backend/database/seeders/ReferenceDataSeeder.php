<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori Jenis Kelamin
        $jenisKelaminId = DB::table('reference_categories')->insertGetId([
            'name' => 'Jenis Kelamin',
            'description' => 'Referensi data jenis kelamin',
            'fields_config' => json_encode([
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 1
                ],
                [
                    'name' => 'name',
                    'label' => 'Nama',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 50
                ]
            ]),
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Data Jenis Kelamin
        DB::table('reference_data')->insert([
            [
                'category_id' => $jenisKelaminId,
                'code' => 'L',
                'name' => 'Laki-laki',
                'description' => 'Jenis kelamin laki-laki',
                'custom_fields' => null,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => $jenisKelaminId,
                'code' => 'P',
                'name' => 'Perempuan',
                'description' => 'Jenis kelamin perempuan',
                'custom_fields' => null,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Kategori Agama
        $agamaId = DB::table('reference_categories')->insertGetId([
            'name' => 'Agama',
            'description' => 'Referensi data agama',
            'fields_config' => json_encode([
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 3
                ],
                [
                    'name' => 'name',
                    'label' => 'Nama',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 50
                ]
            ]),
            'is_active' => true,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Data Agama
        $agamaData = [
            ['ISL', 'Islam'],
            ['KRI', 'Kristen'],
            ['KAT', 'Katolik'],
            ['HIN', 'Hindu'],
            ['BUD', 'Buddha'],
            ['KON', 'Konghucu']
        ];

        foreach ($agamaData as $index => $data) {
            DB::table('reference_data')->insert([
                'category_id' => $agamaId,
                'code' => $data[0],
                'name' => $data[1],
                'description' => 'Agama ' . $data[1],
                'custom_fields' => null,
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Kategori Status Perkawinan
        $statusPerkawinanId = DB::table('reference_categories')->insertGetId([
            'name' => 'Status Perkawinan',
            'description' => 'Referensi data status perkawinan',
            'fields_config' => json_encode([
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 3
                ],
                [
                    'name' => 'name',
                    'label' => 'Nama',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 50
                ]
            ]),
            'is_active' => true,
            'sort_order' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Data Status Perkawinan
        $statusPerkawinanData = [
            ['BLM', 'Belum Menikah'],
            ['NIK', 'Menikah'],
            ['CER', 'Cerai Hidup'],
            ['MEN', 'Cerai Mati']
        ];

        foreach ($statusPerkawinanData as $index => $data) {
            DB::table('reference_data')->insert([
                'category_id' => $statusPerkawinanId,
                'code' => $data[0],
                'name' => $data[1],
                'description' => 'Status perkawinan ' . $data[1],
                'custom_fields' => null,
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Kategori Tingkat Pendidikan
        $tingkatPendidikanId = DB::table('reference_categories')->insertGetId([
            'name' => 'Tingkat Pendidikan',
            'description' => 'Referensi data tingkat pendidikan',
            'fields_config' => json_encode([
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 5
                ],
                [
                    'name' => 'name',
                    'label' => 'Nama',
                    'type' => 'text',
                    'required' => true,
                    'max_length' => 100
                ],
                [
                    'name' => 'level',
                    'label' => 'Level',
                    'type' => 'number',
                    'required' => true
                ]
            ]),
            'is_active' => true,
            'sort_order' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Data Tingkat Pendidikan
        $tingkatPendidikanData = [
            ['SD', 'Sekolah Dasar', 1],
            ['SMP', 'Sekolah Menengah Pertama', 2],
            ['SMA', 'Sekolah Menengah Atas', 3],
            ['SMK', 'Sekolah Menengah Kejuruan', 3],
            ['D1', 'Diploma 1', 4],
            ['D2', 'Diploma 2', 4],
            ['D3', 'Diploma 3', 4],
            ['D4', 'Diploma 4', 5],
            ['S1', 'Sarjana', 5],
            ['S2', 'Magister', 6],
            ['S3', 'Doktor', 7]
        ];

        foreach ($tingkatPendidikanData as $index => $data) {
            DB::table('reference_data')->insert([
                'category_id' => $tingkatPendidikanId,
                'code' => $data[0],
                'name' => $data[1],
                'description' => 'Tingkat pendidikan ' . $data[1],
                'custom_fields' => json_encode(['level' => $data[2]]),
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
