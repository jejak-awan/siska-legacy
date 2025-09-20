<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceData extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'custom_fields',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the category that owns this reference data
     */
    public function category()
    {
        return $this->belongsTo(ReferenceCategory::class, 'category_id');
    }

    /**
     * Scope for active reference data
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered reference data
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope for specific category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the value of a custom field
     */
    public function getCustomField($fieldName)
    {
        return $this->custom_fields[$fieldName] ?? null;
    }

    /**
     * Set the value of a custom field
     */
    public function setCustomField($fieldName, $value)
    {
        $customFields = $this->custom_fields ?? [];
        $customFields[$fieldName] = $value;
        $this->custom_fields = $customFields;
    }

    /**
     * Get formatted display name with code
     */
    public function getDisplayNameAttribute()
    {
        if ($this->code) {
            return "{$this->code} - {$this->name}";
        }
        return $this->name;
    }
}
