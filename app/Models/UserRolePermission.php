<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserRole;
use App\Traits\GlobalAccessors;

class UserRolePermission extends Model {
    use GlobalAccessors;

    protected $table = 'user_role_permissions';

    /**
     * Defines one-to-many relationship
     */
    public function userRole() {
        return $this->belongsTo(UserRole::class, 'role_id')->withDefault();
    }
}
