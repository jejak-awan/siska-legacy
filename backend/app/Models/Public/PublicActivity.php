<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicActivity extends Model
{
    use HasFactory;

    protected $connection = 'public';
    protected $table = 'public_activities';

    protected $fillable = [
        'title',
        'description',
        'category',
        'subcategory',
        'activity_date',
        'location',
        'participants',
        'gallery',
        'achievements',
        'is_featured',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'participants' => 'array',
        'gallery' => 'array',
        'achievements' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'activity_date' => 'date',
        'published_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('activity_date', '>=', now()->subDays($days));
    }

    // Accessors
    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->description), 200);
    }

    public function getFormattedDateAttribute()
    {
        return $this->activity_date->format('d F Y');
    }

    public function getGalleryCountAttribute()
    {
        return is_array($this->gallery) ? count($this->gallery) : 0;
    }

    public function getParticipantCountAttribute()
    {
        return is_array($this->participants) ? count($this->participants) : 0;
    }

    // Methods
    public function isPublished()
    {
        return $this->is_published && $this->published_at !== null;
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function addToGallery($imageUrl)
    {
        $gallery = $this->gallery ?? [];
        $gallery[] = $imageUrl;
        
        $this->update(['gallery' => $gallery]);
    }

    public function removeFromGallery($imageUrl)
    {
        $gallery = $this->gallery ?? [];
        $gallery = array_filter($gallery, fn($url) => $url !== $imageUrl);
        
        $this->update(['gallery' => array_values($gallery)]);
    }

    public function addAchievement($achievement)
    {
        $achievements = $this->achievements ?? [];
        $achievements[] = $achievement;
        
        $this->update(['achievements' => $achievements]);
    }

    public function getCategoryDisplayName()
    {
        $categories = [
            'kesiswaan' => 'Kesiswaan',
            'akademik' => 'Akademik',
            'karakter' => 'Karakter',
            'ekskul' => 'Ekstrakurikuler',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getSubcategoryDisplayName()
    {
        $subcategories = [
            'osis' => 'OSIS',
            'pramuka' => 'Pramuka',
            'paskibra' => 'Paskibra',
            'pmr' => 'PMR',
            'rohis' => 'Rohis',
            'sains' => 'Sains',
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
        ];

        return $subcategories[$this->subcategory] ?? $this->subcategory;
    }
}
