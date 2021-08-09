<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\UserRole;
use App\Models\UserProfileLink;
use App\Models\UserRolePermission;
use App\Models\UserAccessDepartment;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class User extends Authenticatable
{
    use Notifiable;
    use GlobalMutators, GlobalAccessors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Defines one-to-many relationship
     *
     */
    public function allowedDepartments() {
        return $this->hasMany(UserAccessDepartment::class, 'user_id');
    }

    /**
     * Defines one-to-many relationship
     */
    public function roles() {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    /**
     * Defines one-to-one relationship
     */
    public function profileLink() {
        return $this->hasOne(UserProfileLink::class, 'user_id');
    }

    /**
     * Check whether user has linked a profile
     *
     * @return boolean
     */
    public function hasProfile() {
        return !is_null($this->profileLink);
    }

    /**
     * Checks whether user has any of the given role(s)
     *
     * @param array $roles
     */
    public function hasRole(...$roles) {
        $userRoles = $this->roles;
        foreach ($roles as $role) {
            if ($userRoles->contains('role', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns list of valid roles belonging to user
     *
     * @param array $roleList  Array of roles which can perform the action. Eg.: ['admin', 'hod']
     * @return Illuminate\Support\Collection
     */
    public function validRoles(array $roleList) {
        return $this->roles->whereIn('role', $roleList);
    }

    /**
     * Checks whether the user has permission according
     * to the provided roleList
     * Usage:
     * $user->isPermissionValid(['hod', 'office'], 'r')
     *
     * @param array $roleList
     * @param string $permissionToCheck
     */
    public function isPermissionValid(array $roleList, $permissionToCheck) {
        $role_ids = $this->validRoles($roleList)->pluck('id')->toArray();
        $perms = UserRolePermission::select('permission')->distinct()
            ->whereIn('role_id', $role_ids)->where('permission', $permissionToCheck);
        return $perms->count() > 0;
    }

    /**
     * Checks whether user has access to specified department
     * Note: users with admin role have access to all departments, i.e,
     * it will always return true
     *
     * @param int $dept  'id' of department
     * @return bool
     */
    public function hasDepartmentAccess($dept) {
        if ($this->hasRole('admin')) {
            return true;
        }
        return $this->allowedDepartments->where('department_id', $dept)
            ->count() > 0;
    }
}
