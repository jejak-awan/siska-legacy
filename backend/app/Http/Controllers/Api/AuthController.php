<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
                   ->orWhere('email', $request->username)
                   ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif.'],
            ]);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Get user profile based on role
        $profile = $this->getUserProfile($user);

        return response()->json([
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role_type' => $user->role_type,
                'status' => $user->status,
                'profile' => $profile,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_OK);
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ], Response::HTTP_OK);
    }

    /**
     * Get current user information
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $profile = $this->getUserProfile($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role_type' => $user->role_type,
                'status' => $user->status,
                'profile' => $profile,
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Token berhasil diperbarui',
            'token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_OK);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password berhasil diubah'
        ], Response::HTTP_OK);
    }

    /**
     * Get user profile based on role
     */
    private function getUserProfile(User $user)
    {
        switch ($user->role_type) {
            case 'guru':
            case 'wali_kelas':
            case 'bk':
            case 'osis':
            case 'ekstrakurikuler':
                return $user->guru ? [
                    'id' => $user->guru->id,
                    'nip' => $user->guru->nip,
                    'nama_lengkap' => $user->guru->nama_lengkap,
                    'nama_panggilan' => $user->guru->nama_panggilan,
                    'jenis_kelamin' => $user->guru->jenis_kelamin,
                    'nomor_hp' => $user->guru->nomor_hp,
                    'email' => $user->guru->email,
                    'mata_pelajaran' => $user->guru->mata_pelajaran,
                    'is_wali_kelas' => $user->guru->is_wali_kelas,
                    'kelas_wali' => $user->guru->kelas_wali,
                    'is_konselor_bk' => $user->guru->is_konselor_bk,
                    'is_pembina_osis' => $user->guru->is_pembina_osis,
                ] : null;

            case 'siswa':
                return $user->siswa ? [
                    'id' => $user->siswa->id,
                    'nisn' => $user->siswa->nisn,
                    'nis' => $user->siswa->nis,
                    'nama_lengkap' => $user->siswa->nama_lengkap,
                    'nama_panggilan' => $user->siswa->nama_panggilan,
                    'jenis_kelamin' => $user->siswa->jenis_kelamin,
                    'kelas' => $user->siswa->kelas,
                    'angkatan' => $user->siswa->angkatan,
                    'status_siswa' => $user->siswa->status_siswa,
                ] : null;

            case 'orang_tua':
                // For orang tua, we need to get siswa data first
                $siswa = Siswa::whereHas('orangTua', function($query) use ($user) {
                    $query->where('user_id', $user->id); // Assuming orang_tua has user_id
                })->first();
                
                return $siswa ? [
                    'siswa' => [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas' => $siswa->kelas,
                        'angkatan' => $siswa->angkatan,
                    ]
                ] : null;

            default:
                return null;
        }
    }
}