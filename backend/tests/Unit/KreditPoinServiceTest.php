<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Siswa;
use App\Models\KategoriKreditPoin;
use App\Models\KreditPoin;
use App\Services\KreditPoinService;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class KreditPoinServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $kreditPoinService;
    protected $notificationService;
    protected $user;
    protected $siswa;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock notification service
        $this->notificationService = $this->createMock(NotificationService::class);
        
        // Create service instance
        $this->kreditPoinService = new KreditPoinService($this->notificationService);
        
        // Create test data
        $this->user = User::factory()->create(['role_type' => 'admin']);
        $this->siswa = Siswa::factory()->create();
        $this->kategori = KategoriKreditPoin::factory()->create([
            'nama' => 'Test Kategori',
            'jenis' => 'positif',
            'nilai_default' => 10
        ]);
    }

    /**
     * Test create kredit poin with valid data
     */
    public function test_create_kredit_poin_with_valid_data(): void
    {
        Event::fake();
        
        $data = [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => $this->kategori->id,
            'nilai' => 15,
            'deskripsi' => 'Test kredit poin',
            'pelapor_id' => $this->user->id
        ];

        $kreditPoin = $this->kreditPoinService->createKreditPoin($data);

        $this->assertInstanceOf(KreditPoin::class, $kreditPoin);
        $this->assertEquals($this->siswa->id, $kreditPoin->siswa_id);
        $this->assertEquals($this->kategori->id, $kreditPoin->kategori_id);
        $this->assertEquals(15, $kreditPoin->nilai);
        $this->assertEquals('Test kredit poin', $kreditPoin->deskripsi);
        $this->assertEquals('pending', $kreditPoin->status);
        
        // Verify event was dispatched
        Event::assertDispatched(\App\Events\KreditPoinCreated::class);
    }

    /**
     * Test create kredit poin with default nilai
     */
    public function test_create_kredit_poin_with_default_nilai(): void
    {
        $data = [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => $this->kategori->id,
            'deskripsi' => 'Test kredit poin',
            'pelapor_id' => $this->user->id
            // nilai not provided, should use default
        ];

        $kreditPoin = $this->kreditPoinService->createKreditPoin($data);

        $this->assertEquals($this->kategori->nilai_default, $kreditPoin->nilai);
    }

    /**
     * Test create kredit poin with invalid kategori
     */
    public function test_create_kredit_poin_with_invalid_kategori(): void
    {
        $this->expectException(\Exception::class);

        $data = [
            'siswa_id' => $this->siswa->id,
            'kategori_id' => 999, // Non-existent kategori
            'deskripsi' => 'Test kredit poin',
            'pelapor_id' => $this->user->id
        ];

        $this->kreditPoinService->createKreditPoin($data);
    }

    /**
     * Test approve kredit poin
     */
    public function test_approve_kredit_poin(): void
    {
        $kreditPoin = KreditPoin::factory()->create([
            'status' => 'pending'
        ]);

        $result = $this->kreditPoinService->approveKreditPoin($kreditPoin->id, 'Approved by admin');

        $this->assertTrue($result);
        
        $kreditPoin->refresh();
        $this->assertEquals('approved', $kreditPoin->status);
        $this->assertEquals('Approved by admin', $kreditPoin->catatan_admin);
    }

    /**
     * Test reject kredit poin
     */
    public function test_reject_kredit_poin(): void
    {
        $kreditPoin = KreditPoin::factory()->create([
            'status' => 'pending'
        ]);

        $result = $this->kreditPoinService->rejectKreditPoin($kreditPoin->id, 'Data tidak valid');

        $this->assertTrue($result);
        
        $kreditPoin->refresh();
        $this->assertEquals('rejected', $kreditPoin->status);
        $this->assertEquals('Data tidak valid', $kreditPoin->catatan_admin);
    }

    /**
     * Test get kredit poin statistics
     */
    public function test_get_kredit_poin_statistics(): void
    {
        // Create test data
        KreditPoin::factory()->count(5)->create(['status' => 'pending']);
        KreditPoin::factory()->count(3)->create(['status' => 'approved']);
        KreditPoin::factory()->count(2)->create(['status' => 'rejected']);

        $stats = $this->kreditPoinService->getStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('pending', $stats);
        $this->assertArrayHasKey('approved', $stats);
        $this->assertArrayHasKey('rejected', $stats);
        $this->assertEquals(5, $stats['pending']);
        $this->assertEquals(3, $stats['approved']);
        $this->assertEquals(2, $stats['rejected']);
    }

    /**
     * Test get kredit poin by siswa
     */
    public function test_get_kredit_poin_by_siswa(): void
    {
        // Create kredit poin for specific siswa
        KreditPoin::factory()->count(3)->create(['siswa_id' => $this->siswa->id]);
        KreditPoin::factory()->count(2)->create(); // Different siswa

        $kreditPoinList = $this->kreditPoinService->getBySiswa($this->siswa->id);

        $this->assertCount(3, $kreditPoinList);
        foreach ($kreditPoinList as $kp) {
            $this->assertEquals($this->siswa->id, $kp->siswa_id);
        }
    }
}
