<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Traits\GlobalAccessors;

class UserRolePermission extends Model
{
    use GlobalAccessors;

    protected $table = 'user_role_permissions';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['role_id', 'permission', 'created_at'];

    /**
     * Defines one-to-many relationship
     */
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id')->withDefault();
    }

    /**
     * Defines one-to-many relationship
     */
    public function permissions()
    {
        return $this->hasMany(UserRolePermission::class, 'role_id');
    }
}
