<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaturanController;

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::get('/pengaturan/{id}', [PengaturanController::class, 'show']);
    Route::post('/pengaturan', [PengaturanController::class, 'store']);
    Route::put('/pengaturan/{id}', [PengaturanController::class, 'update']);
    Route::delete('/pengaturan/{id}', [PengaturanController::class, 'destroy']);
});
