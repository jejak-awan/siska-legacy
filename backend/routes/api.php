<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\KreditPoinController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\BKController;
use App\Http\Controllers\Api\OSISController;
use App\Http\Controllers\Api\EkstrakurikulerController;
use App\Http\Controllers\Api\CharacterAssessmentController;
use App\Http\Controllers\Api\ReferenceDataController;
use App\Http\Controllers\Api\WhatsAppController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\DocumentationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Include public routes
require __DIR__.'/public.php';

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Test route
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working!',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Documentation routes (public)
Route::get('/health', [DocumentationController::class, 'health']);
Route::get('/version', [DocumentationController::class, 'version']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });
    
    // User management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/activate', [UserController::class, 'activate']);
        Route::post('users/{user}/deactivate', [UserController::class, 'deactivate']);
        Route::get('users/role/{role}', [UserController::class, 'byRole']);
        Route::get('users-statistics', [UserController::class, 'statistics']);
        Route::post('users/bulk-action', [UserController::class, 'bulkAction']);
        Route::post('users/import', [UserController::class, 'import']);
        Route::post('users/export', [UserController::class, 'export']);
    });
    
    // Guru management
    Route::middleware('role:admin,guru,wali_kelas,bk,osis,ekstrakurikuler')->group(function () {
        Route::apiResource('guru', GuruController::class);
        Route::get('guru/wali-kelas/list', [GuruController::class, 'waliKelas']);
        Route::get('guru/konselor-bk/list', [GuruController::class, 'konselorBK']);
        Route::get('guru/pembina-osis/list', [GuruController::class, 'pembinaOSIS']);
        Route::get('guru-statistics', [GuruController::class, 'statistics']);
        Route::get('guru/subject/{subject}', [GuruController::class, 'bySubject']);
    });
    
    // Guru management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('guru/bulk-action', [GuruController::class, 'bulkAction']);
        Route::post('guru/import', [GuruController::class, 'import']);
        Route::post('guru/export', [GuruController::class, 'export']);
        Route::post('guru/{guru}/assign-subjects', [GuruController::class, 'assignSubjects']);
    });
    
    // Siswa management
    Route::middleware('role:admin,guru,wali_kelas,bk,osis,ekstrakurikuler')->group(function () {
        Route::apiResource('siswa', SiswaController::class);
        Route::get('siswa/kelas/{kelasId}', [SiswaController::class, 'byKelas']);
        Route::get('siswa/angkatan/{angkatan}', [SiswaController::class, 'byAngkatan']);
        Route::get('siswa/tingkat/{tingkat}', [SiswaController::class, 'byTingkat']);
        Route::get('siswa/jurusan/{jurusan}', [SiswaController::class, 'byJurusan']);
        Route::get('siswa/statistics/overview', [SiswaController::class, 'statistics']);
    });
    
    // Siswa management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('siswa/bulk-action', [SiswaController::class, 'bulkAction']);
        Route::post('siswa/import', [SiswaController::class, 'import']);
        Route::post('siswa/export', [SiswaController::class, 'export']);
        Route::post('siswa/{siswa}/assign-class', [SiswaController::class, 'assignClass']);
    });
    
    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats']);
        Route::get('/admin', [DashboardController::class, 'adminStats'])->middleware('role:admin');
        Route::get('/guru', [DashboardController::class, 'guruStats'])->middleware('role:guru');
        Route::get('/siswa', [DashboardController::class, 'siswaStats'])->middleware('role:siswa');
    });
    
    // Presensi routes
    Route::middleware('role:admin,guru,wali_kelas,bk,osis,ekstrakurikuler')->group(function () {
        Route::apiResource('presensi', PresensiController::class);
        Route::get('presensi-statistics', [PresensiController::class, 'statistics']);
        Route::get('presensi/user/{userId}', [PresensiController::class, 'byUser']);
        Route::get('presensi/by-user/{userId}', [PresensiController::class, 'byUser']);
        Route::get('presensi/date/{date}', [PresensiController::class, 'byDate']);
    });
    
    // Kredit Poin routes
    Route::middleware('role:admin,guru,wali_kelas,bk,osis,ekstrakurikuler')->group(function () {
        Route::get('kredit-poin-statistics', [KreditPoinController::class, 'statistics']);
        Route::get('kredit-poin/siswa/{siswaId}', [KreditPoinController::class, 'bySiswa']);
        Route::get('kredit-poin/pending', [KreditPoinController::class, 'pending']);
        Route::apiResource('kredit-poin', KreditPoinController::class);
    });
    
    // Kredit Poin approval (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('kredit-poin/{id}/approve', [KreditPoinController::class, 'approve']);
        Route::post('kredit-poin/{id}/reject', [KreditPoinController::class, 'reject']);
    });
    
    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store'])->middleware('role:admin');
        Route::get('/stats', [NotificationController::class, 'statistics']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::post('/send-to-users', [NotificationController::class, 'sendToUsers'])->middleware('role:admin');
        Route::get('/{id}', [NotificationController::class, 'show']);
        Route::put('/{id}', [NotificationController::class, 'update']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::post('/{id}/mark-archived', [NotificationController::class, 'markAsArchived']);
    });
    
    // BK (Bimbingan Konseling) routes
    Route::middleware('role:admin,bk')->group(function () {
        // Konseling routes
        Route::prefix('konseling')->group(function () {
            Route::get('/', [BKController::class, 'indexKonseling']);
            Route::post('/', [BKController::class, 'storeKonseling']);
            Route::get('/statistics', [BKController::class, 'statisticsKonseling']);
            Route::get('/{konseling}', [BKController::class, 'showKonseling']);
            Route::put('/{konseling}', [BKController::class, 'updateKonseling']);
            Route::delete('/{konseling}', [BKController::class, 'destroyKonseling']);
        });
        
        // Home Visit routes
        Route::prefix('home-visit')->group(function () {
            Route::get('/', [BKController::class, 'indexHomeVisit']);
            Route::post('/', [BKController::class, 'storeHomeVisit']);
            Route::get('/statistics', [BKController::class, 'statisticsHomeVisit']);
            Route::get('/{homeVisit}', [BKController::class, 'showHomeVisit']);
            Route::put('/{homeVisit}', [BKController::class, 'updateHomeVisit']);
            Route::delete('/{homeVisit}', [BKController::class, 'destroyHomeVisit']);
        });
        
        // Combined BK statistics
        Route::get('bk-statistics', [BKController::class, 'statistics']);
    });
    
    // OSIS routes
    Route::middleware('role:admin,osis')->group(function () {
        Route::get('osis-statistics', [OSISController::class, 'statistics']);
        Route::get('osis-kegiatan/upcoming', [OSISController::class, 'upcoming']);
        Route::get('osis-kegiatan/ongoing', [OSISController::class, 'ongoing']);
        Route::apiResource('osis-kegiatan', OSISController::class);
    });
    
    // Ekstrakurikuler routes
    Route::middleware('role:admin,ekstrakurikuler')->group(function () {
        Route::apiResource('ekstrakurikuler', EkstrakurikulerController::class);
        Route::get('ekstrakurikuler-statistics', [EkstrakurikulerController::class, 'statistics']);
        Route::get('ekstrakurikuler/{ekstrakurikuler}/students', [EkstrakurikulerController::class, 'getStudents']);
        Route::post('ekstrakurikuler/register-student', [EkstrakurikulerController::class, 'registerStudent']);
        Route::post('ekstrakurikuler/unregister-student', [EkstrakurikulerController::class, 'unregisterStudent']);
    });
    
    // Student access to ekstrakurikuler
    Route::middleware('role:siswa')->group(function () {
        Route::get('ekstrakurikuler/available/{siswa}', [EkstrakurikulerController::class, 'getAvailableForStudent']);
    });
    
    // WhatsApp routes
    Route::middleware('role:admin')->group(function () {
        Route::post('whatsapp/send-text', [WhatsAppController::class, 'sendTextMessage']);
        Route::post('whatsapp/send-template', [WhatsAppController::class, 'sendTemplateMessage']);
        Route::post('whatsapp/send-media', [WhatsAppController::class, 'sendMediaMessage']);
        Route::post('whatsapp/send-bulk', [WhatsAppController::class, 'sendBulkMessages']);
        Route::get('whatsapp/message-status/{messageId}', [WhatsAppController::class, 'getMessageStatus']);
        Route::get('whatsapp/logs', [WhatsAppController::class, 'getMessageLogs']);
        Route::get('whatsapp/statistics', [WhatsAppController::class, 'getMessageStatistics']);
        Route::get('whatsapp/recent', [WhatsAppController::class, 'getRecentMessages']);
        Route::post('whatsapp/cleanup-logs', [WhatsAppController::class, 'cleanupOldLogs']);
        Route::get('whatsapp/test-connection', [WhatsAppController::class, 'testConnection']);
    });
    
    // Character Assessment routes
    Route::prefix('character-assessment')->group(function () {
        Route::get('dimensions', [CharacterAssessmentController::class, 'getDimensions']);
        Route::get('statistics', [CharacterAssessmentController::class, 'getStatistics']);
        Route::get('student/{studentId}', [CharacterAssessmentController::class, 'getStudentAssessments']);
        Route::post('/', [CharacterAssessmentController::class, 'store']);
        Route::put('/{id}', [CharacterAssessmentController::class, 'update']);
        Route::post('/{id}/submit', [CharacterAssessmentController::class, 'submit']);
        Route::post('/{id}/approve', [CharacterAssessmentController::class, 'approve']);
        Route::post('/{id}/reject', [CharacterAssessmentController::class, 'reject']);
        Route::delete('/{id}', [CharacterAssessmentController::class, 'destroy']);
        Route::get('report', [CharacterAssessmentController::class, 'getReport']);
    });
    
    // Reference Data routes
    Route::prefix('reference-data')->group(function () {
        Route::get('categories', [ReferenceDataController::class, 'getCategories']);
        Route::post('categories', [ReferenceDataController::class, 'createCategory']);
        Route::put('categories/{category}', [ReferenceDataController::class, 'updateCategory']);
        Route::delete('categories/{category}', [ReferenceDataController::class, 'deleteCategory']);
        
        Route::get('category/{categoryId}/data', [ReferenceDataController::class, 'getDataByCategory']);
        Route::post('data', [ReferenceDataController::class, 'createData']);
        Route::put('data/{data}', [ReferenceDataController::class, 'updateData']);
        Route::delete('data/{data}', [ReferenceDataController::class, 'deleteData']);
        
        Route::get('category/{categoryId}/select', [ReferenceDataController::class, 'getForSelect']);
        Route::get('search', [ReferenceDataController::class, 'search']);
    });
    
    // Image upload and management routes
    Route::prefix('images')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
        Route::post('upload-multiple', [ImageController::class, 'uploadMultiple']);
        Route::post('resize', [ImageController::class, 'resize']);
        Route::delete('delete', [ImageController::class, 'delete']);
        Route::get('info', [ImageController::class, 'info']);
    });
    
    // WhatsApp webhook routes (public)
    Route::get('whatsapp/webhook', [WhatsAppController::class, 'webhookVerify']);
    Route::post('whatsapp/webhook', [WhatsAppController::class, 'webhookHandler']);
});