<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     description="Role model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="admin"),
 *     @OA\Property(property="description", type="string", example="Administrator role"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string"), example={"user.create", "user.read", "user.update", "user.delete"}),
 *     @OA\Property(property="level", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'permissions',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permissions' => 'array',
        'level' => 'integer',
    ];

    /**
     * Get the users assigned to this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')->withTimestamps();
    }

    /**
     * Add permission to role.
     */
    public function addPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
        }
        return $this;
    }

    /**
     * Remove permission from role.
     */
    public function removePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $this->permissions = array_values(array_diff($permissions, [$permission]));
        return $this;
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withPivot('granted')
                    ->withTimestamps();
    }

    /**
     * Check if role has permission.
     */
    public function hasPermission($permissionName): bool
    {
        return $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('granted', true)
            ->exists();
    }

    /**
     * Grant permission to role.
     */
    public function grantPermission($permissionId): void
    {
        $this->permissions()->syncWithoutDetaching([
            $permissionId => ['granted' => true]
        ]);
    }

    /**
     * Revoke permission from role.
     */
    public function revokePermission($permissionId): void
    {
        $this->permissions()->syncWithoutDetaching([
            $permissionId => ['granted' => false]
        ]);
    }

    /**
     * Scope to filter by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}