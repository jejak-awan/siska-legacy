<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelasRelation', 'tahunAjaran', 'orangTua']);

        // Filter by status
        if ($request->has('status_aktif')) {
            $query->where('status_aktif', $request->boolean('status_aktif'));
        }

        // Filter by status siswa
        if ($request->has('status_siswa')) {
            $query->where('status_siswa', $request->status_siswa);
        }

        // Filter by kelas
        if ($request->has('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter by angkatan
        if ($request->has('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Search by name, NISN, or NIS
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $siswa = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Siswa retrieved successfully',
            'data' => $siswa
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
            
            // Siswa data
            'nisn' => 'required|string|size:10|unique:siswa',
            'nis' => 'required|string|size:10|unique:siswa',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'nik' => 'required|string|size:16|unique:siswa',
            'no_kk' => 'required|string|size:16',
            'alamat_lengkap' => 'required|string',
            'kelurahan' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'kabupaten_kota' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50',
            'kelas' => 'required|string|max:20',
            'angkatan' => 'required|integer',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tanggal_masuk' => 'required|date',
            'status_siswa' => 'required|in:Aktif,Pindah,Lulus,DO,Mengundurkan Diri',
        ]);

        // Create user first
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_type' => 'siswa',
            'status' => 'aktif',
        ]);

        // Create siswa profile
        $siswaData = $request->except(['username', 'email', 'password']);
        $siswaData['user_id'] = $user->id;

        // Handle JSON fields
        if ($request->has('riwayat_penyakit')) {
            $siswaData['riwayat_penyakit'] = is_array($request->riwayat_penyakit) 
                ? $request->riwayat_penyakit 
                : json_decode($request->riwayat_penyakit, true);
        }

        if ($request->has('hobi')) {
            $siswaData['hobi'] = is_array($request->hobi) 
                ? $request->hobi 
                : json_decode($request->hobi, true);
        }

        if ($request->has('cita_cita')) {
            $siswaData['cita_cita'] = is_array($request->cita_cita) 
                ? $request->cita_cita 
                : json_decode($request->cita_cita, true);
        }

        if ($request->has('prestasi')) {
            $siswaData['prestasi'] = is_array($request->prestasi) 
                ? $request->prestasi 
                : json_decode($request->prestasi, true);
        }

        if ($request->has('ekstrakurikuler')) {
            $siswaData['ekstrakurikuler'] = is_array($request->ekstrakurikuler) 
                ? $request->ekstrakurikuler 
                : json_decode($request->ekstrakurikuler, true);
        }

        $siswa = Siswa::create($siswaData);
        $siswa->load(['user', 'kelasRelation', 'tahunAjaran']);

        return response()->json([
            'message' => 'Siswa created successfully',
            'data' => $siswa
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'kelasRelation', 'tahunAjaran', 'orangTua']);

        return response()->json([
            'message' => 'Siswa retrieved successfully',
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => ['sometimes', 'string', 'size:10', Rule::unique('siswa')->ignore($siswa->id)],
            'nis' => ['sometimes', 'string', 'size:10', Rule::unique('siswa')->ignore($siswa->id)],
            'nik' => ['sometimes', 'string', 'size:16', Rule::unique('siswa')->ignore($siswa->id)],
            'nama_lengkap' => 'sometimes|string|max:100',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'tempat_lahir' => 'sometimes|string|max:50',
            'tanggal_lahir' => 'sometimes|date',
            'agama' => 'sometimes|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_siswa' => 'sometimes|in:Aktif,Pindah,Lulus,DO,Mengundurkan Diri',
            'status_aktif' => 'sometimes|boolean',
        ]);

        $updateData = $request->except(['username', 'email', 'password']);

        // Handle JSON fields
        if ($request->has('riwayat_penyakit')) {
            $updateData['riwayat_penyakit'] = is_array($request->riwayat_penyakit) 
                ? $request->riwayat_penyakit 
                : json_decode($request->riwayat_penyakit, true);
        }

        if ($request->has('hobi')) {
            $updateData['hobi'] = is_array($request->hobi) 
                ? $request->hobi 
                : json_decode($request->hobi, true);
        }

        $siswa->update($updateData);

        return response()->json([
            'message' => 'Siswa updated successfully',
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // Delete associated user as well
        $siswa->user()->delete();
        $siswa->delete();

        return response()->json([
            'message' => 'Siswa deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Get siswa by kelas
     */
    public function byKelas($kelasId)
    {
        $siswa = Siswa::byKelas($kelasId)->active()->get();

        return response()->json([
            'message' => 'Siswa by kelas retrieved successfully',
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Get siswa by angkatan
     */
    public function byAngkatan($angkatan)
    {
        $siswa = Siswa::byAngkatan($angkatan)->active()->get();

        return response()->json([
            'message' => 'Siswa by angkatan retrieved successfully',
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Get siswa statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Siswa::count(),
            'active' => Siswa::active()->count(),
            'by_status' => Siswa::selectRaw('status_siswa, count(*) as count')
                               ->groupBy('status_siswa')
                               ->get(),
            'by_gender' => Siswa::selectRaw('jenis_kelamin, count(*) as count')
                               ->groupBy('jenis_kelamin')
                               ->get(),
            'by_angkatan' => Siswa::selectRaw('angkatan, count(*) as count')
                                 ->groupBy('angkatan')
                                 ->orderBy('angkatan', 'desc')
                                 ->get(),
        ];

        return response()->json([
            'message' => 'Siswa statistics retrieved successfully',
            'data' => $stats
        ], Response::HTTP_OK);
    }

    /**
     * Bulk operations for siswa
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:assign_class,remove_class,delete,activate,deactivate',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id',
            'kelas_id' => 'sometimes|exists:kelas,id',
        ]);

        $siswaIds = $request->siswa_ids;
        $action = $request->action;
        $affected = 0;
        
        switch ($action) {
            case 'assign_class':
                if (!$request->kelas_id) {
                    return response()->json([
                        'message' => 'Kelas ID is required for assign_class action'
                    ], Response::HTTP_BAD_REQUEST);
                }
                $affected = Siswa::whereIn('id', $siswaIds)->update(['kelas_id' => $request->kelas_id]);
                break;
            case 'remove_class':
                $affected = Siswa::whereIn('id', $siswaIds)->update(['kelas_id' => null]);
                break;
            case 'activate':
                $siswas = Siswa::whereIn('id', $siswaIds)->with('user')->get();
                foreach ($siswas as $siswa) {
                    $siswa->user()->update(['status' => 'aktif']);
                }
                $affected = $siswas->count();
                break;
            case 'deactivate':
                $siswas = Siswa::whereIn('id', $siswaIds)->with('user')->get();
                foreach ($siswas as $siswa) {
                    $siswa->user()->update(['status' => 'nonaktif']);
                }
                $affected = $siswas->count();
                break;
            case 'delete':
                // Delete associated users as well
                $siswas = Siswa::whereIn('id', $siswaIds)->with('user')->get();
                foreach ($siswas as $siswa) {
                    $siswa->user()->delete();
                    $siswa->delete();
                }
                $affected = $siswas->count();
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
     * Import siswa from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB for student data
        ]);

        // This is a placeholder for file import functionality
        // In a real implementation, you would use Laravel Excel
        
        return response()->json([
            'message' => 'Siswa import feature coming soon',
            'data' => [
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
            ]
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Export siswa to CSV/Excel
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,xlsx',
            'kelas_id' => 'sometimes|exists:kelas,id',
            'tingkat' => 'sometimes|in:10,11,12',
            'jurusan' => 'sometimes|in:IPA,IPS,Bahasa',
            'jenis_kelamin' => 'sometimes|in:L,P',
        ]);

        // This is a placeholder for file export functionality
        // In a real implementation, you would use Laravel Excel
        
        return response()->json([
            'message' => 'Siswa export feature coming soon',
            'data' => [
                'format' => $request->format,
                'filters' => $request->only(['kelas_id', 'tingkat', 'jurusan', 'jenis_kelamin']),
            ]
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Assign siswa to kelas
     */
    public function assignClass(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update([
            'kelas_id' => $request->kelas_id,
        ]);

        return response()->json([
            'message' => 'Siswa assigned to class successfully',
            'data' => $siswa->fresh(['kelasRelation', 'user'])
        ], Response::HTTP_OK);
    }

    /**
     * Get siswa by tingkat (grade level)
     */
    public function byTingkat($tingkat)
    {
        $siswa = Siswa::whereHas('kelasRelation', function($q) use ($tingkat) {
                    $q->where('tingkat', $tingkat);
                 })
                 ->with(['user', 'kelasRelation'])
                 ->get();

        return response()->json([
            'message' => "Siswa tingkat {$tingkat} retrieved successfully",
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Get siswa by jurusan
     */
    public function byJurusan($jurusan)
    {
        $siswa = Siswa::whereHas('kelasRelation', function($q) use ($jurusan) {
                    $q->where('jurusan', $jurusan);
                 })
                 ->with(['user', 'kelasRelation'])
                 ->get();

        return response()->json([
            'message' => "Siswa jurusan {$jurusan} retrieved successfully",
            'data' => $siswa
        ], Response::HTTP_OK);
    }
}