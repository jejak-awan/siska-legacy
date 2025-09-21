<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Development Mode - No Swagger)
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| This file is used when SWAGGER_DEV_ENABLED=false for faster development.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()->toISOString()
    ]);
});

// Version info
Route::get('/version', function () {
    return response()->json([
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'timestamp' => now()->toISOString()
    ]);
});

// Development routes (no Swagger annotations)
Route::middleware('auth:sanctum')->group(function () {
    
    // User routes (development version)
    Route::apiResource('users', App\Http\Controllers\Api\UserControllerDev::class);
    Route::get('users-statistics', [App\Http\Controllers\Api\UserControllerDev::class, 'statistics']);
    
    // Auth routes
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/auth/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::post('/auth/refresh', [App\Http\Controllers\Api\AuthController::class, 'refresh']);
    Route::post('/auth/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword']);
    
    // Dashboard routes
    Route::get('/dashboard/admin-stats', [App\Http\Controllers\Api\DashboardController::class, 'adminStats']);
    Route::get('/dashboard/guru-stats', [App\Http\Controllers\Api\DashboardController::class, 'guruStats']);
    Route::get('/dashboard/siswa-stats', [App\Http\Controllers\Api\DashboardController::class, 'siswaStats']);
    
    // Kredit Poin routes
    Route::middleware('role:admin,guru,bk,osis')->group(function () {
        Route::apiResource('kredit-poin', App\Http\Controllers\Api\KreditPoinController::class);
        Route::get('kredit-poin-statistics', [App\Http\Controllers\Api\KreditPoinController::class, 'statistics']);
    });
    
    // Presensi routes
    Route::middleware('role:admin,guru,wali_kelas')->group(function () {
        Route::apiResource('presensi', App\Http\Controllers\Api\PresensiController::class);
        Route::get('presensi-statistics', [App\Http\Controllers\Api\PresensiController::class, 'statistics']);
    });
    
    // Profile Sekolah routes
    Route::middleware('role:admin')->group(function () {
        Route::put('profile-sekolah/basic-info', [App\Http\Controllers\Api\ProfileSekolahController::class, 'updateBasicInfo']);
        Route::put('profile-sekolah/school-details', [App\Http\Controllers\Api\ProfileSekolahController::class, 'updateSchoolDetails']);
        Route::post('profile-sekolah/upload-logo', [App\Http\Controllers\Api\ProfileSekolahController::class, 'uploadLogo']);
        Route::post('profile-sekolah/upload-headmaster', [App\Http\Controllers\Api\ProfileSekolahController::class, 'uploadHeadmaster']);
        Route::apiResource('profile-sekolah', App\Http\Controllers\Api\ProfileSekolahController::class);
    });
    
    // Other routes...
    // Add more routes as needed without Swagger annotations
});
