<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserRole;

class UserRolePermission extends Model {
    protected $table = 'user_role_permissions';

    public function userRole() {
        return $this->belongsTo(UserRole::class, 'role_id')->withDefault();
    }
}
