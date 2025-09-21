<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'resource',
        'is_system',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withPivot('granted')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function isGrantedForRole($roleId): bool
    {
        $rolePermission = $this->roles()
            ->where('role_id', $roleId)
            ->wherePivot('granted', true)
            ->first();

        return $rolePermission !== null;
    }

    public function grantToRole($roleId): void
    {
        $this->roles()->syncWithoutDetaching([
            $roleId => ['granted' => true]
        ]);
    }

    public function revokeFromRole($roleId): void
    {
        $this->roles()->syncWithoutDetaching([
            $roleId => ['granted' => false]
        ]);
    }

    public static function getGroupedByModule(): array
    {
        return self::active()
            ->orderBy('module')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('module')
            ->toArray();
    }

    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'inactive' => self::where('is_active', false)->count(),
            'by_module' => self::selectRaw('module, count(*) as count')
                ->groupBy('module')
                ->get()
                ->pluck('count', 'module'),
            'by_action' => self::selectRaw('action, count(*) as count')
                ->groupBy('action')
                ->get()
                ->pluck('count', 'action'),
        ];
    }
}
