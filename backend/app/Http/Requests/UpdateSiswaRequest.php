<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $siswaId = $this->route('siswa'); // Assuming route parameter is 'siswa'

        return [
            // Account information (usually not updated, but if needed)
            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($siswaId, 'id')
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($siswaId, 'id')
            ],
            'password' => [
                'sometimes',
                'string',
                'min:6',
                'max:255'
            ],

            // Student identity
            'nisn' => [
                'sometimes',
                'string',
                'size:10',
                'regex:/^\d{10}$/',
                Rule::unique('siswa', 'nisn')->ignore($siswaId)
            ],
            'nis' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')->ignore($siswaId)
            ],
            'nik' => [
                'sometimes',
                'string',
                'size:16',
                'regex:/^\d{16}$/',
                Rule::unique('siswa', 'nik')->ignore($siswaId)
            ],

            // Personal information
            'nama_lengkap' => [
                'sometimes',
                'string',
                'min:2',
                'max:100'
            ],
            'nama_panggilan' => [
                'nullable',
                'string',
                'max:50'
            ],
            'jenis_kelamin' => [
                'sometimes',
                'string',
                Rule::in(['L', 'P'])
            ],
            'tempat_lahir' => [
                'sometimes',
                'string',
                'min:2',
                'max:100'
            ],
            'tanggal_lahir' => [
                'sometimes',
                'date',
                'before:today'
            ],
            'agama' => [
                'sometimes',
                'string',
                Rule::in(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])
            ],
            'kewarganegaraan' => [
                'sometimes',
                'string',
                Rule::in(['WNI', 'WNA'])
            ],

            // Academic information
            'kelas_id' => [
                'nullable',
                'integer',
                'exists:kelas,id'
            ],
            'angkatan' => [
                'sometimes',
                'string',
                'regex:/^\d{4}$/'
            ],
            'status_siswa' => [
                'sometimes',
                'string',
                Rule::in(['Aktif', 'Lulus', 'Pindah', 'Keluar', 'Mengundurkan Diri'])
            ],

            // Contact information
            'nomor_hp' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/'
            ],
            'email_pribadi' => [
                'nullable',
                'string',
                'email',
                'max:255'
            ],
            'alamat_lengkap' => [
                'sometimes',
                'string',
                'min:10',
                'max:500'
            ],
            'kelurahan' => [
                'sometimes',
                'string',
                'max:100'
            ],
            'kecamatan' => [
                'sometimes',
                'string',
                'max:100'
            ],
            'kabupaten_kota' => [
                'sometimes',
                'string',
                'max:100'
            ],
            'provinsi' => [
                'sometimes',
                'string',
                'max:100'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Account information
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 20 karakter',
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore',
            'username.unique' => 'Username sudah digunakan',
            
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            
            'password.min' => 'Password minimal 6 karakter',

            // Student identity
            'nisn.size' => 'NISN harus 10 digit',
            'nisn.regex' => 'NISN harus berupa angka',
            'nisn.unique' => 'NISN sudah digunakan',
            
            'nis.unique' => 'NIS sudah digunakan',
            
            'nik.size' => 'NIK harus 16 digit',
            'nik.regex' => 'NIK harus berupa angka',
            'nik.unique' => 'NIK sudah digunakan',

            // Personal information
            'nama_lengkap.min' => 'Nama minimal 2 karakter',
            'nama_lengkap.max' => 'Nama maksimal 100 karakter',
            
            'nama_panggilan.max' => 'Nama panggilan maksimal 50 karakter',
            
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            
            'tempat_lahir.min' => 'Tempat lahir minimal 2 karakter',
            
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan',
            
            'agama.in' => 'Agama tidak valid',
            
            'kewarganegaraan.in' => 'Kewarganegaraan tidak valid',

            // Academic information
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            
            'angkatan.regex' => 'Angkatan harus 4 digit tahun',
            
            'status_siswa.in' => 'Status siswa tidak valid',

            // Contact information
            'nomor_hp.regex' => 'Nomor HP tidak valid',
            
            'email_pribadi.email' => 'Format email pribadi tidak valid',
            
            'alamat_lengkap.min' => 'Alamat minimal 10 karakter',
            'alamat_lengkap.max' => 'Alamat maksimal 500 karakter',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
            'nisn' => 'NISN',
            'nis' => 'NIS',
            'nik' => 'NIK',
            'nama_lengkap' => 'nama lengkap',
            'nama_panggilan' => 'nama panggilan',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'agama' => 'agama',
            'kewarganegaraan' => 'kewarganegaraan',
            'kelas_id' => 'kelas',
            'angkatan' => 'angkatan',
            'status_siswa' => 'status siswa',
            'nomor_hp' => 'nomor HP',
            'email_pribadi' => 'email pribadi',
            'alamat_lengkap' => 'alamat lengkap',
            'kelurahan' => 'kelurahan',
            'kecamatan' => 'kecamatan',
            'kabupaten_kota' => 'kabupaten/kota',
            'provinsi' => 'provinsi',
        ];
    }
}