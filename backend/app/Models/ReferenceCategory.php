<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'fields_config',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'fields_config' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the reference data for this category
     */
    public function referenceData()
    {
        return $this->hasMany(ReferenceData::class, 'category_id');
    }

    /**
     * Get active reference data for this category
     */
    public function activeReferenceData()
    {
        return $this->hasMany(ReferenceData::class, 'category_id')->where('is_active', true);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the count of reference data
     */
    public function getDataCountAttribute()
    {
        return $this->referenceData()->count();
    }

    /**
     * Get the count of active reference data
     */
    public function getActiveDataCountAttribute()
    {
        return $this->activeReferenceData()->count();
    }
}
