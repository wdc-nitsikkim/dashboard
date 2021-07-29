<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\UserRolePermission;

class UserRole extends Model {
    protected $table = 'user_roles';

    public function permissions() {
        return $this->hasMany(UserRolePermission::class, 'role_id');
    }

    public function hasPermission($permissionToCheck) {
        return false;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
