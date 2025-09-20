<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orang_tua';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'siswa_id',
        'nama_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nomor_hp_ayah',
        'email_ayah',
        'nama_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'nomor_hp_ibu',
        'email_ibu',
        'nama_wali',
        'nik_wali',
        'hubungan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'nomor_hp_wali',
        'email_wali',
        'status_ekonomi',
        'penghasilan_total',
        'jumlah_tanggungan',
        'kepemilikan_rumah',
        'penerima_pkh',
        'nomor_hp_darurat',
        'alamat_ortu',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'penghasilan_ayah' => 'decimal:2',
        'penghasilan_ibu' => 'decimal:2',
        'penghasilan_wali' => 'decimal:2',
        'penghasilan_total' => 'decimal:2',
        'jumlah_tanggungan' => 'integer',
        'penerima_pkh' => 'boolean',
    ];

    /**
     * Get the siswa that owns this orang tua record.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Get primary contact number (ayah, ibu, or wali).
     */
    public function getPrimaryContactAttribute()
    {
        return $this->nomor_hp_ayah ?: $this->nomor_hp_ibu ?: $this->nomor_hp_wali;
    }

    /**
     * Get primary contact name.
     */
    public function getPrimaryContactNameAttribute()
    {
        if ($this->nomor_hp_ayah) {
            return $this->nama_ayah;
        } elseif ($this->nomor_hp_ibu) {
            return $this->nama_ibu;
        } elseif ($this->nomor_hp_wali) {
            return $this->nama_wali;
        }
        return null;
    }

    /**
     * Get total family income.
     */
    public function getTotalIncomeAttribute()
    {
        return $this->penghasilan_total ?: 
               ($this->penghasilan_ayah + $this->penghasilan_ibu + $this->penghasilan_wali);
    }

    /**
     * Check if family receives government assistance.
     */
    public function receivesAssistance()
    {
        return $this->penerima_pkh;
    }

    /**
     * Check if family is economically disadvantaged.
     */
    public function isEconomicallyDisadvantaged()
    {
        return in_array($this->status_ekonomi, ['Kurang Mampu', 'Tidak Mampu']);
    }

    /**
     * Get emergency contact numbers.
     */
    public function getEmergencyContactsAttribute()
    {
        $contacts = [];
        
        if ($this->nomor_hp_ayah) {
            $contacts[] = ['name' => $this->nama_ayah, 'phone' => $this->nomor_hp_ayah, 'relation' => 'Ayah'];
        }
        
        if ($this->nomor_hp_ibu) {
            $contacts[] = ['name' => $this->nama_ibu, 'phone' => $this->nomor_hp_ibu, 'relation' => 'Ibu'];
        }
        
        if ($this->nomor_hp_wali) {
            $contacts[] = ['name' => $this->nama_wali, 'phone' => $this->nomor_hp_wali, 'relation' => $this->hubungan_wali];
        }
        
        if ($this->nomor_hp_darurat) {
            $contacts[] = ['name' => 'Kontak Darurat', 'phone' => $this->nomor_hp_darurat, 'relation' => 'Darurat'];
        }
        
        return $contacts;
    }

    /**
     * Scope to filter by economic status.
     */
    public function scopeByEconomicStatus($query, $status)
    {
        return $query->where('status_ekonomi', $status);
    }

    /**
     * Scope to filter PKH recipients.
     */
    public function scopePkhRecipients($query)
    {
        return $query->where('penerima_pkh', true);
    }
}