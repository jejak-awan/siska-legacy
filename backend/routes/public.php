<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicContentController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| These routes are accessible without authentication and are designed
| for public consumption (landing page, public content, etc.)
|
*/

// Public content routes
Route::prefix('public')->group(function () {
    
    // Activities
    Route::get('activities', [PublicContentController::class, 'getActivities']);
    
    // News/Posts
    Route::get('news', [PublicContentController::class, 'getNews']);
    
    // Programs
    Route::get('programs', [PublicContentController::class, 'getPrograms']);
    
    // Featured content
    Route::get('featured', [PublicContentController::class, 'getFeaturedContent']);
    
    // Content statistics
    Route::get('stats', [PublicContentController::class, 'getContentStats']);
    
    // Search
    Route::get('search', [PublicContentController::class, 'search']);
});

// Health check for public API
Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
    ]);
});
