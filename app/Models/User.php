<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserAccessDepartment;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function allowedDepartments() {
        return $this->hasMany(UserAccessDepartment::class, 'user_id');
    }

    public function roles() {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function hasRole($role) {
        return $this->roles->contains('role', $role);
    }

    public function validRoles(array $roleList) {
        return $this->roles->whereIn('role', $roleList);
    }

    public function isPermissionValid(array $roleList, $permissionToCheck) {
        $role_ids = $this->validRoles($roleList)->pluck('id')->toArray();
        $perms = UserRolePermission::select('permission')->distinct()
            ->whereIn('role_id', $role_ids)->where('permission', $permissionToCheck)
            ->get();
        return $perms->count() > 0;
    }
}
