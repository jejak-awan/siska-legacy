<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'phone_number',
        'message',
        'status',
        'message_id',
        'response_data',
        'error_message',
        'user_id',
        'template_name',
        'template_params'
    ];

    protected $casts = [
        'response_data' => 'array',
        'template_params' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPhoneNumber($query, $phoneNumber)
    {
        return $query->where('phone_number', $phoneNumber);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'sent' => 'Terkirim',
            'delivered' => 'Terkirim',
            'read' => 'Dibaca',
            'failed' => 'Gagal',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'blue',
            'delivered' => 'green',
            'read' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }

    public function getPhoneNumberFormattedAttribute(): string
    {
        // Format phone number for display
        $phone = $this->phone_number;
        if (str_starts_with($phone, '62')) {
            return '+62 ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        }
        return $phone;
    }
}
