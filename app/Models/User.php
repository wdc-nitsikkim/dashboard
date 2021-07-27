<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\UserPermission;
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

    /* custom model functions */

    public function permissions() {
        return $this->hasMany(UserPermission::class, 'user_id');
    }

    public function departments() {
        return $this->hasMany(UserAccessDepartment::class, 'user_id');
    }
}
