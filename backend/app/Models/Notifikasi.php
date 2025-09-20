<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'status',
        'data',
        'action_url',
        'action_text',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors & Mutators
    public function getTipeLabelAttribute(): string
    {
        return match($this->tipe) {
            'info' => 'Informasi',
            'warning' => 'Peringatan',
            'error' => 'Error',
            'success' => 'Berhasil',
            default => 'Tidak Diketahui'
        };
    }

    public function getTipeColorAttribute(): string
    {
        return match($this->tipe) {
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'success' => 'green',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'unread' => 'Belum Dibaca',
            'read' => 'Sudah Dibaca',
            'archived' => 'Diarsipkan',
            default => 'Tidak Diketahui'
        };
    }

    // Methods
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsArchived(): void
    {
        $this->update(['status' => 'archived']);
    }
}
