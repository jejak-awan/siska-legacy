<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaRequest extends FormRequest
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
        return [
            // Account information
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:users,username'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255'
            ],

            // Student identity
            'nisn' => [
                'required',
                'string',
                'size:10',
                'regex:/^\d{10}$/',
                'unique:siswa,nisn'
            ],
            'nis' => [
                'required',
                'string',
                'max:20',
                'unique:siswa,nis'
            ],
            'nik' => [
                'required',
                'string',
                'size:16',
                'regex:/^\d{16}$/',
                'unique:siswa,nik'
            ],

            // Personal information
            'nama_lengkap' => [
                'required',
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
                'required',
                'string',
                Rule::in(['L', 'P'])
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today'
            ],
            'agama' => [
                'required',
                'string',
                Rule::in(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])
            ],
            'kewarganegaraan' => [
                'required',
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
                'required',
                'string',
                'regex:/^\d{4}$/'
            ],
            'status_siswa' => [
                'required',
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
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'kelurahan' => [
                'required',
                'string',
                'max:100'
            ],
            'kecamatan' => [
                'required',
                'string',
                'max:100'
            ],
            'kabupaten_kota' => [
                'required',
                'string',
                'max:100'
            ],
            'provinsi' => [
                'required',
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
            'username.required' => 'Username wajib diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 20 karakter',
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore',
            'username.unique' => 'Username sudah digunakan',
            
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',

            // Student identity
            'nisn.required' => 'NISN wajib diisi',
            'nisn.size' => 'NISN harus 10 digit',
            'nisn.regex' => 'NISN harus berupa angka',
            'nisn.unique' => 'NISN sudah digunakan',
            
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah digunakan',
            
            'nik.required' => 'NIK wajib diisi',
            'nik.size' => 'NIK harus 16 digit',
            'nik.regex' => 'NIK harus berupa angka',
            'nik.unique' => 'NIK sudah digunakan',

            // Personal information
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.min' => 'Nama minimal 2 karakter',
            'nama_lengkap.max' => 'Nama maksimal 100 karakter',
            
            'nama_panggilan.max' => 'Nama panggilan maksimal 50 karakter',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tempat_lahir.min' => 'Tempat lahir minimal 2 karakter',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan',
            
            'agama.required' => 'Agama wajib dipilih',
            'agama.in' => 'Agama tidak valid',
            
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih',
            'kewarganegaraan.in' => 'Kewarganegaraan tidak valid',

            // Academic information
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            
            'angkatan.required' => 'Angkatan wajib diisi',
            'angkatan.regex' => 'Angkatan harus 4 digit tahun',
            
            'status_siswa.required' => 'Status siswa wajib dipilih',
            'status_siswa.in' => 'Status siswa tidak valid',

            // Contact information
            'nomor_hp.regex' => 'Nomor HP tidak valid',
            
            'email_pribadi.email' => 'Format email pribadi tidak valid',
            
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
            'alamat_lengkap.min' => 'Alamat minimal 10 karakter',
            'alamat_lengkap.max' => 'Alamat maksimal 500 karakter',
            
            'kelurahan.required' => 'Kelurahan wajib diisi',
            'kecamatan.required' => 'Kecamatan wajib diisi',
            'kabupaten_kota.required' => 'Kabupaten/Kota wajib diisi',
            'provinsi.required' => 'Provinsi wajib diisi',
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