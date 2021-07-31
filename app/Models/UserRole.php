<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\UserRolePermission;

class UserRole extends Model {
    protected $table = 'user_roles';

    /**
     * Returns list of permissions belonging to role
     */
    public function permissions() {
        return $this->hasMany(UserRolePermission::class, 'role_id');
    }

    /**
     * Checks whether role has given permission
     *
     * @param string $permissionToCheck
     * @return bool
     */
    public function hasPermission($permissionToCheck) {
        return false;
    }

    /**
     * Defines many-to-one relationship
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
