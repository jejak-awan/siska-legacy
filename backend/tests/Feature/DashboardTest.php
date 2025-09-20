<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Presensi;
use App\Models\KreditPoin;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->adminUser = User::factory()->create(['role_type' => 'admin']);
        
        // Create test data
        User::factory()->count(5)->create();
        Siswa::factory()->count(10)->create();
        Guru::factory()->count(3)->create();
        Presensi::factory()->count(20)->create();
        KreditPoin::factory()->count(15)->create();
        Notifikasi::factory()->count(8)->create();
    }

    /**
     * Test admin can get dashboard statistics
     */
    public function test_admin_can_get_dashboard_statistics(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/admin');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'users' => [
                        'total',
                        'active',
                        'inactive',
                        'by_role'
                    ],
                    'guru' => [
                        'total',
                        'active',
                        'wali_kelas',
                        'bk'
                    ],
                    'siswa' => [
                        'total',
                        'active',
                        'by_kelas'
                    ],
                    'presensi' => [
                        'today',
                        'hadir_today',
                        'terlambat_today',
                        'alpha_today'
                    ],
                    'kredit_poin' => [
                        'pending',
                        'approved_today',
                        'total_positif',
                        'total_negatif'
                    ],
                    'notifications' => [
                        'total',
                        'unread',
                        'today'
                    ]
                ]
            ]);
    }

    /**
     * Test unauthenticated user cannot get dashboard statistics
     */
    public function test_unauthenticated_user_cannot_get_dashboard_statistics(): void
    {
        $response = $this->getJson('/api/dashboard/admin');

        $response->assertStatus(401);
    }

    /**
     * Test non-admin user cannot get admin dashboard statistics
     */
    public function test_non_admin_user_cannot_get_admin_dashboard_statistics(): void
    {
        $regularUser = User::factory()->create(['role_type' => 'guru']);
        $token = $regularUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/admin');

        $response->assertStatus(403);
    }

    /**
     * Test general dashboard stats endpoint
     */
    public function test_authenticated_user_can_get_general_dashboard_stats(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    /**
     * Test dashboard statistics contain correct data
     */
    public function test_dashboard_statistics_contain_correct_data(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/admin');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        
        // Verify user statistics
        $this->assertGreaterThanOrEqual(6, $data['users']['total']); // admin + 5 users
        $this->assertGreaterThanOrEqual(1, $data['users']['active']);
        
        // Verify siswa statistics
        $this->assertGreaterThanOrEqual(10, $data['siswa']['total']);
        $this->assertGreaterThanOrEqual(1, $data['siswa']['active']);
        
        // Verify guru statistics
        $this->assertGreaterThanOrEqual(3, $data['guru']['total']);
        $this->assertGreaterThanOrEqual(1, $data['guru']['active']);
        
        // Verify presensi statistics
        $this->assertGreaterThanOrEqual(0, $data['presensi']['today']);
        
        // Verify kredit poin statistics
        $this->assertGreaterThanOrEqual(0, $data['kredit_poin']['pending']);
        
        // Verify notifications statistics
        $this->assertGreaterThanOrEqual(8, $data['notifications']['total']);
        $this->assertGreaterThanOrEqual(0, $data['notifications']['unread']);
    }

    /**
     * Test dashboard statistics are updated in real-time
     */
    public function test_dashboard_statistics_are_updated_in_real_time(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // Get initial statistics
        $initialResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/admin');

        $initialData = $initialResponse->json('data');
        $initialNotificationCount = $initialData['notifications']['total'];

        // Create a new notification
        Notifikasi::factory()->create();

        // Get updated statistics
        $updatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/dashboard/admin');

        $updatedData = $updatedResponse->json('data');
        $updatedNotificationCount = $updatedData['notifications']['total'];

        // Verify notification count increased
        $this->assertEquals($initialNotificationCount + 1, $updatedNotificationCount);
    }
}
