<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role_type' => $this->faker->randomElement(['admin', 'guru', 'siswa', 'wali_kelas', 'bk', 'osis', 'ekstrakurikuler']),
            'status' => 'aktif',
            'two_factor_enabled' => false,
        ];
    }
}
