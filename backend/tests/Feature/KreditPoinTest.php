<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\KategoriKreditPoin;
use App\Models\KreditPoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KreditPoinTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $siswa;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create(['role_type' => 'admin']);
        
        // Create test siswa
        $this->siswa = Siswa::factory()->create();
        
        // Create test kategori
        $this->kategori = KategoriKreditPoin::factory()->create([
            'nama' => 'Test Kategori',
            'jenis' => 'positif',
            'nilai_default' => 10
        ]);
    }

    /**
     * Test authenticated user can create kredit poin
     */
    public function test_authenticated_user_can_create_kredit_poin(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $kreditPoinData = [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => $this->kategori->id,
            'nilai' => 15,
            'deskripsi' => 'Test kredit poin',
            'pelapor_id' => $this->user->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/kredit-poin', $kreditPoinData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'siswa_id',
                    'kategori_id',
                    'nilai',
                    'deskripsi',
                    'status'
                ]
            ])
            ->assertJson([
                'data' => [
                    'siswa_id' => $this->siswa->id,
                    'kategori_id' => $this->kategori->id,
                    'nilai' => 15,
                    'deskripsi' => 'Test kredit poin',
                    'status' => 'pending'
                ]
            ]);

        // Verify kredit poin was created in database
        $this->assertDatabaseHas('kredit_poin', [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => $this->kategori->id,
            'nilai' => 15,
            'status' => 'pending'
        ]);
    }

    /**
     * Test unauthenticated user cannot create kredit poin
     */
    public function test_unauthenticated_user_cannot_create_kredit_poin(): void
    {
        $kreditPoinData = [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => $this->kategori->id,
            'nilai' => 15,
            'deskripsi' => 'Test kredit poin',
            'pelapor_id' => $this->user->id
        ];

        $response = $this->postJson('/api/kredit-poin', $kreditPoinData);

        $response->assertStatus(401);
    }

    /**
     * Test kredit poin creation with validation errors
     */
    public function test_kredit_poin_creation_requires_valid_data(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/kredit-poin', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['siswa_id', 'kategori_id', 'deskripsi', 'pelapor_id']);
    }

    /**
     * Test authenticated user can get kredit poin list
     */
    public function test_authenticated_user_can_get_kredit_poin_list(): void
    {
        // Create some kredit poin records
        KreditPoin::factory()->count(3)->create();

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/kredit-poin');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'siswa_id',
                            'kategori_id',
                            'nilai',
                            'deskripsi',
                            'status'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test admin can approve kredit poin
     */
    public function test_admin_can_approve_kredit_poin(): void
    {
        $kreditPoin = KreditPoin::factory()->create([
            'status' => 'pending'
        ]);

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/kredit-poin/{$kreditPoin->id}/approve");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kredit poin approved successfully'
            ]);

        // Verify status was updated
        $this->assertDatabaseHas('kredit_poin', [
            'id' => $kreditPoin->id,
            'status' => 'approved'
        ]);
    }

    /**
     * Test admin can reject kredit poin
     */
    public function test_admin_can_reject_kredit_poin(): void
    {
        $kreditPoin = KreditPoin::factory()->create([
            'status' => 'pending'
        ]);

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/kredit-poin/{$kreditPoin->id}/reject", [
            'catatan_admin' => 'Data tidak valid'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kredit poin rejected successfully'
            ]);

        // Verify status was updated
        $this->assertDatabaseHas('kredit_poin', [
            'id' => $kreditPoin->id,
            'status' => 'rejected',
            'catatan_admin' => 'Data tidak valid'
        ]);
    }
}
