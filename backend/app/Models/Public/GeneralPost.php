<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPost extends Model
{
    use HasFactory;

    protected $connection = 'public';
    protected $table = 'general_posts';

    protected $fillable = [
        'title',
        'content',
        'category',
        'subcategory',
        'status',
        'tags',
        'attachments',
        'author_role',
        'author_id',
        'is_featured',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'attachments' => 'array',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
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

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Accessors
    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->content), 150);
    }

    public function getFormattedTagsAttribute()
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    // Methods
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    public function canBeEditedBy($user)
    {
        // Admin and staff can edit all posts
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        // Users can only edit their own posts
        return $this->author_role === $user->role && $this->author_id === $user->id;
    }

    public function canBeDeletedBy($user)
    {
        // Only admin can delete posts
        return $user->role === 'admin';
    }
}
