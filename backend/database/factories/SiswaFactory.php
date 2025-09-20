<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nisn' => $this->faker->unique()->numerify('##########'),
            'nis' => $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => $this->faker->name(),
            'nama_panggilan' => $this->faker->firstName(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'kewarganegaraan' => 'Indonesia',
            'nik' => $this->faker->unique()->numerify('################'),
            'no_kk' => $this->faker->unique()->numerify('################'),
            'alamat_lengkap' => $this->faker->address(),
            'nomor_hp_siswa' => $this->faker->phoneNumber(),
            'email_siswa' => $this->faker->unique()->safeEmail(),
            'angkatan' => $this->faker->numberBetween(2020, 2025),
            'tahun_ajaran_id' => 1,
            'status_siswa' => 'Aktif',
            'status_aktif' => true,
        ];
    }
}
