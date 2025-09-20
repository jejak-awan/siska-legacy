<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentAudit extends Model
{
    use HasFactory;

    protected $connection = 'public';
    protected $table = 'content_audits';

    protected $fillable = [
        'content_id',
        'content_type',
        'action',
        'user_id',
        'user_role',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    // Scopes
    public function scopeByContent($query, $contentId, $contentType)
    {
        return $query->where('content_id', $contentId)
                    ->where('content_type', $contentType);
    }

    public function scopeByUser($query, $userId, $userRole = null)
    {
        $query = $query->where('user_id', $userId);
        
        if ($userRole) {
            $query->where('user_role', $userRole);
        }
        
        return $query;
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getActionDisplayNameAttribute()
    {
        $actions = [
            'create' => 'Membuat',
            'update' => 'Memperbarui',
            'delete' => 'Menghapus',
            'publish' => 'Mempublikasikan',
            'approve' => 'Menyetujui',
            'reject' => 'Menolak',
            'feature' => 'Menampilkan di Featured',
            'unfeature' => 'Menghapus dari Featured',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    public function getContentTypeDisplayNameAttribute()
    {
        $types = [
            'general_post' => 'Post Umum',
            'program' => 'Program',
            'public_activity' => 'Aktivitas Publik',
        ];

        return $types[$this->content_type] ?? $this->content_type;
    }

    public function getFormattedChangesAttribute()
    {
        if (!$this->changes) {
            return 'Tidak ada perubahan yang dicatat';
        }

        $formatted = [];
        foreach ($this->changes as $field => $change) {
            if (is_array($change)) {
                $formatted[] = "{$field}: " . json_encode($change);
            } else {
                $formatted[] = "{$field}: {$change}";
            }
        }

        return implode(', ', $formatted);
    }

    // Methods
    public static function log($contentId, $contentType, $action, $user, $changes = null)
    {
        return self::create([
            'content_id' => $contentId,
            'content_type' => $contentType,
            'action' => $action,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getShortIpAttribute()
    {
        return substr($this->ip_address, 0, 10) . '...';
    }

    public function getShortUserAgentAttribute()
    {
        return substr($this->user_agent, 0, 50) . '...';
    }
}
