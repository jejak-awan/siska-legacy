<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guru::with(['user', 'kelasWali']);

        // Filter by status
        if ($request->has('status_aktif')) {
            $query->where('status_aktif', $request->boolean('status_aktif'));
        }

        // Filter by wali kelas
        if ($request->has('is_wali_kelas')) {
            $query->where('is_wali_kelas', $request->boolean('is_wali_kelas'));
        }

        // Filter by konselor BK
        if ($request->has('is_konselor_bk')) {
            $query->where('is_konselor_bk', $request->boolean('is_konselor_bk'));
        }

        // Filter by pembina OSIS
        if ($request->has('is_pembina_osis')) {
            $query->where('is_pembina_osis', $request->boolean('is_pembina_osis'));
        }

        // Search by name or NIP
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nuptk', 'like', "%{$search}%");
            });
        }

        $guru = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Guru retrieved successfully',
            'data' => $guru
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // User data
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            
            // Guru data
            'nip' => 'required|string|size:18|unique:guru',
            'nuptk' => 'nullable|string|size:16|unique:guru',
            'nama_lengkap' => 'required|string|max:100',
            'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'alamat_lengkap' => 'required|string',
            'kelurahan' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'kabupaten_kota' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50',
            'nomor_hp' => 'required|string|max:15',
            'jenis_ptk' => 'required|in:Guru Kelas,Guru Mapel,Tenaga Kependidikan',
            'status_kepegawaian' => 'required|in:PNS,PPPK,GTT,PTT,Honorer',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'pendidikan_terakhir' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
        ]);

        // Create user first
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_type' => 'guru',
            'status' => 'aktif',
        ]);

        // Create guru profile
        $guruData = $request->except(['username', 'email', 'password']);
        $guruData['user_id'] = $user->id;

        // Handle JSON fields
        if ($request->has('kelas_yang_diajar')) {
            $guruData['kelas_yang_diajar'] = is_array($request->kelas_yang_diajar) 
                ? $request->kelas_yang_diajar 
                : json_decode($request->kelas_yang_diajar, true);
        }

        if ($request->has('sertifikat_lainnya')) {
            $guruData['sertifikat_lainnya'] = is_array($request->sertifikat_lainnya) 
                ? $request->sertifikat_lainnya 
                : json_decode($request->sertifikat_lainnya, true);
        }

        if ($request->has('ekstrakurikuler')) {
            $guruData['ekstrakurikuler'] = is_array($request->ekstrakurikuler) 
                ? $request->ekstrakurikuler 
                : json_decode($request->ekstrakurikuler, true);
        }

        if ($request->has('tugas_tambahan')) {
            $guruData['tugas_tambahan'] = is_array($request->tugas_tambahan) 
                ? $request->tugas_tambahan 
                : json_decode($request->tugas_tambahan, true);
        }

        $guru = Guru::create($guruData);
        $guru->load('user');

        return response()->json([
            'message' => 'Guru created successfully',
            'data' => $guru
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        $guru->load(['user', 'kelasWali']);

        return response()->json([
            'message' => 'Guru retrieved successfully',
            'data' => $guru
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => ['sometimes', 'string', 'size:18', Rule::unique('guru')->ignore($guru->id)],
            'nuptk' => ['nullable', 'string', 'size:16', Rule::unique('guru')->ignore($guru->id)],
            'nama_lengkap' => 'sometimes|string|max:100',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'tempat_lahir' => 'sometimes|string|max:50',
            'tanggal_lahir' => 'sometimes|date',
            'agama' => 'sometimes|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'nomor_hp' => 'sometimes|string|max:15',
            'status_aktif' => 'sometimes|boolean',
            'is_wali_kelas' => 'sometimes|boolean',
            'is_konselor_bk' => 'sometimes|boolean',
            'is_pembina_osis' => 'sometimes|boolean',
        ]);

        $updateData = $request->except(['username', 'email', 'password']);

        // Handle JSON fields
        if ($request->has('kelas_yang_diajar')) {
            $updateData['kelas_yang_diajar'] = is_array($request->kelas_yang_diajar) 
                ? $request->kelas_yang_diajar 
                : json_decode($request->kelas_yang_diajar, true);
        }

        $guru->update($updateData);

        return response()->json([
            'message' => 'Guru updated successfully',
            'data' => $guru
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        // Delete associated user as well
        $guru->user()->delete();
        $guru->delete();

        return response()->json([
            'message' => 'Guru deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Get wali kelas
     */
    public function waliKelas()
    {
        $waliKelas = Guru::waliKelas()->active()->get();

        return response()->json([
            'message' => 'Wali kelas retrieved successfully',
            'data' => $waliKelas
        ], Response::HTTP_OK);
    }

    /**
     * Get konselor BK
     */
    public function konselorBK()
    {
        $konselorBK = Guru::konselorBK()->active()->get();

        return response()->json([
            'message' => 'Konselor BK retrieved successfully',
            'data' => $konselorBK
        ], Response::HTTP_OK);
    }

    /**
     * Get pembina OSIS
     */
    public function pembinaOSIS()
    {
        $pembinaOSIS = Guru::pembinaOSIS()->active()->get();

        return response()->json([
            'message' => 'Pembina OSIS retrieved successfully',
            'data' => $pembinaOSIS
        ], Response::HTTP_OK);
    }

    /**
     * Get guru statistics
     */
    public function statistics()
    {
        $stats = [
            'totalGuru' => Guru::count(),
            'activeGuru' => Guru::where('status_aktif', true)->count(),
            'inactiveGuru' => Guru::where('status_aktif', false)->count(),
            'waliKelas' => Guru::where('is_wali_kelas', true)->count(),
            'konselorBK' => Guru::where('is_konselor_bk', true)->count(),
            'pembinaOSIS' => Guru::where('is_pembina_osis', true)->count(),
            'guruKelas' => Guru::where('jenis_ptk', 'Guru Kelas')->count(),
            'guruMapel' => Guru::where('jenis_ptk', 'Guru Mapel')->count(),
            'tendik' => Guru::where('jenis_ptk', 'Tenaga Kependidikan')->count(),
            'pns' => Guru::where('status_kepegawaian', 'PNS')->count(),
            'nonPns' => Guru::whereIn('status_kepegawaian', ['PPPK', 'GTT', 'PTT', 'Honorer'])->count(),
            'recentGuru' => Guru::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json([
            'message' => 'Guru statistics retrieved successfully',
            'data' => $stats
        ], Response::HTTP_OK);
    }

    /**
     * Bulk operations for guru
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_wali_kelas,remove_wali_kelas',
            'guru_ids' => 'required|array|min:1',
            'guru_ids.*' => 'exists:guru,id',
        ]);

        $guruIds = $request->guru_ids;
        $action = $request->action;
        $affected = 0;
        
        switch ($action) {
            case 'activate':
                $affected = Guru::whereIn('id', $guruIds)->update(['status_aktif' => true]);
                break;
            case 'deactivate':
                $affected = Guru::whereIn('id', $guruIds)->update(['status_aktif' => false]);
                break;
            case 'delete':
                // Delete associated users as well
                $gurus = Guru::whereIn('id', $guruIds)->with('user')->get();
                foreach ($gurus as $guru) {
                    $guru->user()->delete();
                    $guru->delete();
                }
                $affected = $gurus->count();
                break;
            case 'assign_wali_kelas':
                $affected = Guru::whereIn('id', $guruIds)->update(['is_wali_kelas' => true]);
                break;
            case 'remove_wali_kelas':
                $affected = Guru::whereIn('id', $guruIds)->update(['is_wali_kelas' => false]);
                break;
        }

        return response()->json([
            'message' => "Bulk {$action} completed successfully",
            'data' => [
                'affected_count' => $affected,
                'action' => $action
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Import guru from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120', // 5MB for guru data
        ]);

        // This is a placeholder for file import functionality
        // In a real implementation, you would use Laravel Excel
        
        return response()->json([
            'message' => 'Guru import feature coming soon',
            'data' => [
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
            ]
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Export guru to CSV/Excel
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,xlsx',
            'status_aktif' => 'sometimes|boolean',
            'jenis_ptk' => 'sometimes|in:Guru Kelas,Guru Mapel,Tenaga Kependidikan',
            'status_kepegawaian' => 'sometimes|in:PNS,PPPK,GTT,PTT,Honorer',
        ]);

        // This is a placeholder for file export functionality
        // In a real implementation, you would use Laravel Excel
        
        return response()->json([
            'message' => 'Guru export feature coming soon',
            'data' => [
                'format' => $request->format,
                'filters' => $request->only(['status_aktif', 'jenis_ptk', 'status_kepegawaian']),
            ]
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Assign subjects to guru
     */
    public function assignSubjects(Request $request, Guru $guru)
    {
        $request->validate([
            'mata_pelajaran' => 'required|array',
            'mata_pelajaran.*' => 'string|max:100',
            'kelas_yang_diajar' => 'sometimes|array',
            'kelas_yang_diajar.*' => 'string|max:50',
        ]);

        $guru->update([
            'mata_pelajaran' => $request->mata_pelajaran,
            'kelas_yang_diajar' => $request->kelas_yang_diajar ?? $guru->kelas_yang_diajar,
        ]);

        return response()->json([
            'message' => 'Subjects assigned successfully',
            'data' => $guru->fresh()
        ], Response::HTTP_OK);
    }

    /**
     * Get guru by subject
     */
    public function bySubject($subject)
    {
        $guru = Guru::whereJsonContains('mata_pelajaran', $subject)
                   ->active()
                   ->with('user')
                   ->get();

        return response()->json([
            'message' => "Guru teaching {$subject} retrieved successfully",
            'data' => $guru
        ], Response::HTTP_OK);
    }
}