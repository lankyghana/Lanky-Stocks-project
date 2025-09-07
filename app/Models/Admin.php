<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'status', 'permissions'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    /**
     * Boot the model.
     */
    public static function boot()
    {
        parent::boot();
        
        // Set default values for missing columns
        static::creating(function ($admin) {
            // Set default status if column exists
            try {
                if (Schema::hasColumn('admins', 'status')) {
                    $admin->status = $admin->status ?? 1;
                }
            } catch (Exception $e) {
                // Column doesn't exist, skip
            }
            
            // Set default permissions if column exists
            try {
                if (Schema::hasColumn('admins', 'permissions')) {
                    $admin->permissions = $admin->permissions ?? [];
                }
            } catch (Exception $e) {
                // Column doesn't exist, skip
            }
        });
    }

    public function hasPermission($permission)
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        // Check if permissions column exists and use custom permissions if available
        try {
            if (Schema::hasColumn('admins', 'permissions')) {
                // Use custom permissions from database
                $permissions = $this->permissions ?? [];
                return in_array($permission, $permissions) || in_array('*', $permissions);
            } else {
                // Fall back to role-based permissions only if column doesn't exist
                return $this->getRolePermissions($this->role, $permission);
            }
        } catch (Exception $e) {
            // Fall back to role-based permissions on error
            return $this->getRolePermissions($this->role, $permission);
        }
    }

    public function hasAnyPermission($permissions)
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get role-based permissions when permissions column doesn't exist
     */
    private function getRolePermissions($role, $permission)
    {
        $rolePermissions = [
            'admin' => [
                // Basic permissions - admin role should have custom permissions set
                'users.view', 'services.view', 'orders.view', 'categories.view',
                'deposits.view', 'withdrawals.view', 'transactions.view',
                'system.view', 'settings.view', 'reports.view'
            ],
            'manager' => [
                'users.view', 'users.manage', 'users.edit',
                'services.view', 'services.manage', 'services.edit',
                'orders.view', 'orders.manage', 'orders.edit',
                'categories.view', 'categories.manage',
                'deposits.view', 'withdrawals.view', 'transactions.view',
                'system.view', 'settings.view',
                'reports.view', 'reports.generate',
                'tickets.view', 'tickets.manage', 'tickets.reply',
                'providers.view', 'providers.manage'
            ],
            'viewer' => [
                'users.view', 'services.view', 'orders.view', 'categories.view',
                'deposits.view', 'withdrawals.view', 'transactions.view',
                'system.view', 'settings.view',
                'reports.view', 'tickets.view', 'providers.view'
            ]
        ];
        
        return isset($rolePermissions[$role]) && in_array($permission, $rolePermissions[$role]);
    }

    public function hasAllPermissions($permissions)
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        return empty(array_diff($permissions, $this->permissions ?? []));
    }

    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = json_encode($value);
    }
}
