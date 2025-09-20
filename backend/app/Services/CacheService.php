<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = [
        'short' => 5,      // 5 minutes
        'medium' => 30,    // 30 minutes
        'long' => 120,     // 2 hours
        'very_long' => 1440, // 24 hours
    ];

    /**
     * Cache keys
     */
    const CACHE_KEYS = [
        'dashboard_stats' => 'dashboard:stats:',
        'user_profile' => 'user:profile:',
        'kredit_poin_stats' => 'kredit_poin:stats:',
        'presensi_stats' => 'presensi:stats:',
        'siswa_list' => 'siswa:list:',
        'guru_list' => 'guru:list:',
        'kategori_kredit_poin' => 'kategori:kredit_poin:',
        'notifications' => 'notifications:',
    ];

    /**
     * Get cached data or execute callback and cache result
     */
    public static function remember(string $key, int $duration, callable $callback, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->remember($key, $duration, $callback);
            }
            return Cache::remember($key, $duration, $callback);
        } catch (\Exception $e) {
            Log::warning('Cache error: ' . $e->getMessage(), [
                'key' => $key,
                'duration' => $duration,
                'tags' => $tags
            ]);
            // Fallback to callback execution without caching
            return $callback();
        }
    }

    /**
     * Get cached data or execute callback and cache result with tags
     */
    public static function rememberWithTags(string $key, int $duration, callable $callback, array $tags)
    {
        return self::remember($key, $duration, $callback, $tags);
    }

    /**
     * Forget cache by key
     */
    public static function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::warning('Cache forget error: ' . $e->getMessage(), ['key' => $key]);
            return false;
        }
    }

    /**
     * Forget cache by tags
     */
    public static function forgetByTags(array $tags): bool
    {
        try {
            Cache::tags($tags)->flush();
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache tags flush error: ' . $e->getMessage(), ['tags' => $tags]);
            return false;
        }
    }

    /**
     * Clear all cache
     */
    public static function clearAll(): bool
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache flush error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get dashboard statistics with caching
     */
    public static function getDashboardStats(string $role = 'admin', callable $callback)
    {
        $key = self::CACHE_KEYS['dashboard_stats'] . $role;
        return self::remember($key, self::CACHE_DURATION['short'], $callback, ['dashboard', 'stats']);
    }

    /**
     * Get user profile with caching
     */
    public static function getUserProfile(int $userId, callable $callback)
    {
        $key = self::CACHE_KEYS['user_profile'] . $userId;
        return self::remember($key, self::CACHE_DURATION['medium'], $callback, ['user', 'profile']);
    }

    /**
     * Get kredit poin statistics with caching
     */
    public static function getKreditPoinStats(callable $callback)
    {
        $key = self::CACHE_KEYS['kredit_poin_stats'];
        return self::remember($key, self::CACHE_DURATION['short'], $callback, ['kredit_poin', 'stats']);
    }

    /**
     * Get presensi statistics with caching
     */
    public static function getPresensiStats(callable $callback)
    {
        $key = self::CACHE_KEYS['presensi_stats'];
        return self::remember($key, self::CACHE_DURATION['short'], $callback, ['presensi', 'stats']);
    }

    /**
     * Get siswa list with caching
     */
    public static function getSiswaList(array $filters = [], callable $callback)
    {
        $key = self::CACHE_KEYS['siswa_list'] . md5(serialize($filters));
        return self::remember($key, self::CACHE_DURATION['medium'], $callback, ['siswa', 'list']);
    }

    /**
     * Get guru list with caching
     */
    public static function getGuruList(array $filters = [], callable $callback)
    {
        $key = self::CACHE_KEYS['guru_list'] . md5(serialize($filters));
        return self::remember($key, self::CACHE_DURATION['medium'], $callback, ['guru', 'list']);
    }

    /**
     * Get kategori kredit poin with caching
     */
    public static function getKategoriKreditPoin(callable $callback)
    {
        $key = self::CACHE_KEYS['kategori_kredit_poin'];
        return self::remember($key, self::CACHE_DURATION['long'], $callback, ['kategori', 'kredit_poin']);
    }

    /**
     * Get notifications with caching
     */
    public static function getNotifications(int $userId, callable $callback)
    {
        $key = self::CACHE_KEYS['notifications'] . $userId;
        return self::remember($key, self::CACHE_DURATION['short'], $callback, ['notifications', 'user']);
    }

    /**
     * Invalidate dashboard cache
     */
    public static function invalidateDashboardCache(): void
    {
        self::forgetByTags(['dashboard', 'stats']);
    }

    /**
     * Invalidate user cache
     */
    public static function invalidateUserCache(int $userId): void
    {
        self::forget(self::CACHE_KEYS['user_profile'] . $userId);
        self::forgetByTags(['user', 'profile']);
    }

    /**
     * Invalidate kredit poin cache
     */
    public static function invalidateKreditPoinCache(): void
    {
        self::forgetByTags(['kredit_poin', 'stats']);
    }

    /**
     * Invalidate presensi cache
     */
    public static function invalidatePresensiCache(): void
    {
        self::forgetByTags(['presensi', 'stats']);
    }

    /**
     * Invalidate siswa cache
     */
    public static function invalidateSiswaCache(): void
    {
        self::forgetByTags(['siswa', 'list']);
    }

    /**
     * Invalidate guru cache
     */
    public static function invalidateGuruCache(): void
    {
        self::forgetByTags(['guru', 'list']);
    }

    /**
     * Invalidate notifications cache
     */
    public static function invalidateNotificationsCache(int $userId = null): void
    {
        if ($userId) {
            self::forget(self::CACHE_KEYS['notifications'] . $userId);
        }
        self::forgetByTags(['notifications', 'user']);
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats(): array
    {
        try {
            $redis = Cache::getRedis();
            $info = $redis->info();
            
            return [
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'connected_clients' => $info['connected_clients'] ?? 'N/A',
                'total_commands_processed' => $info['total_commands_processed'] ?? 'N/A',
                'keyspace_hits' => $info['keyspace_hits'] ?? 'N/A',
                'keyspace_misses' => $info['keyspace_misses'] ?? 'N/A',
                'hit_rate' => isset($info['keyspace_hits'], $info['keyspace_misses']) 
                    ? round($info['keyspace_hits'] / ($info['keyspace_hits'] + $info['keyspace_misses']) * 100, 2) 
                    : 'N/A'
            ];
        } catch (\Exception $e) {
            Log::warning('Cache stats error: ' . $e->getMessage());
            return [
                'error' => 'Unable to retrieve cache statistics'
            ];
        }
    }
}
