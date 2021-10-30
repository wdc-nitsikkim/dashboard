<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Traits\GlobalAccessors;

class UserRole extends Model
{
    use GlobalAccessors;

    protected $table = 'user_roles';

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'role'];

    /**
     * Returns list of permissions belonging to role
     */
    public function permissions()
    {
        return $this->hasMany(UserRolePermission::class, 'role_id');
    }

    /**
     * Defines many-to-one relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
