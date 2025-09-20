<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Sistem Manajemen Kesiswaan API",
 *     version="1.0.0",
 *     description="API untuk Sistem Manajemen Kesiswaan Terintegrasi",
 *     @OA\Contact(
 *         email="jejakawan007@gmail.com",
 *         name="Awan Setiawan"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class DocumentationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/health",
     *     summary="Health Check",
     *     description="Check if the API is running",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="API is healthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ok"),
     *             @OA\Property(property="message", type="string", example="API is running"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'API is running',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/version",
     *     summary="Get API Version",
     *     description="Get the current API version information",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="Version information",
     *         @OA\JsonContent(
     *             @OA\Property(property="version", type="string", example="1.0.0"),
     *             @OA\Property(property="laravel_version", type="string", example="11.35"),
     *             @OA\Property(property="php_version", type="string", example="8.3.0")
     *         )
     *     )
     * )
     */
    public function version()
    {
        return response()->json([
            'version' => '1.0.0',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION
        ]);
    }
}
