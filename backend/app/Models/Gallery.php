<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'subcategory',
        'image_path',
        'thumbnail_path',
        'original_filename',
        'file_size',
        'mime_type',
        'width',
        'height',
        'metadata',
        'uploaded_by_role',
        'uploaded_by_id',
        'is_featured',
        'is_public',
        'status',
        'tags',
        'published_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'published_at' => 'datetime',
        'width' => 'integer',
        'height' => 'integer',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeBySubcategory($query, $subcategory)
    {
        return $query->where('subcategory', $subcategory);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByUploader($query, $role, $id)
    {
        return $query->where('uploaded_by_role', $role)
                    ->where('uploaded_by_id', $id);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : $this->image_url;
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFormattedTagsAttribute()
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    public function getCategoryDisplayNameAttribute()
    {
        $categories = [
            'kegiatan' => 'Kegiatan',
            'prestasi' => 'Prestasi',
            'ekstrakurikuler' => 'Ekstrakurikuler',
            'osis' => 'OSIS',
            'akademik' => 'Akademik',
            'karakter' => 'Karakter',
            'umum' => 'Umum',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getSubcategoryDisplayNameAttribute()
    {
        $subcategories = [
            'upacara' => 'Upacara',
            'lomba' => 'Lomba',
            'seminar' => 'Seminar',
            'workshop' => 'Workshop',
            'pramuka' => 'Pramuka',
            'paskibra' => 'Paskibra',
            'pmr' => 'PMR',
            'rohis' => 'Rohis',
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'sains' => 'Sains',
            'bahasa' => 'Bahasa',
        ];

        return $subcategories[$this->subcategory] ?? $this->subcategory;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'published' => 'success',
            'archived' => 'warning',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Methods
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function archive()
    {
        $this->update([
            'status' => 'archived',
        ]);
    }

    public function canBeEditedBy($user)
    {
        // Admin can edit all galleries
        if ($user->role === 'admin') {
            return true;
        }

        // Users can edit their own galleries
        return $this->uploaded_by_role === $user->role && $this->uploaded_by_id === $user->id;
    }

    public function canBeDeletedBy($user)
    {
        // Admin can delete all galleries
        if ($user->role === 'admin') {
            return true;
        }

        // Users can delete their own galleries
        return $this->uploaded_by_role === $user->role && $this->uploaded_by_id === $user->id;
    }

    public function addTag($tag)
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag($tag)
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        $this->update(['tags' => array_values($tags)]);
    }

    public function getImageDimensions()
    {
        if ($this->width && $this->height) {
            return $this->width . 'x' . $this->height;
        }
        return 'Unknown';
    }

    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo()
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    public function isDocument()
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}