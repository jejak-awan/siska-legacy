<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| These routes are accessible without authentication and are designed
| for public consumption (landing page, public content, etc.)
|
*/

// TODO: Implement PublicContentController
// Route::prefix('public')->group(function () {
//     Route::get('activities', [PublicContentController::class, 'getActivities']);
//     Route::get('news', [PublicContentController::class, 'getNews']);
//     Route::get('programs', [PublicContentController::class, 'getPrograms']);
//     Route::get('featured', [PublicContentController::class, 'getFeaturedContent']);
//     Route::get('stats', [PublicContentController::class, 'getContentStats']);
//     Route::get('search', [PublicContentController::class, 'search']);
// });

// Health check for public API
Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
    ]);
});
