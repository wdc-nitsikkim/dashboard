<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\UserPermission;
use App\UserAccessDepartment;

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

    public function getAllowedDepartments($id = null) {
        $id = ($id == null ? Auth::id() : $id);
        return UserAccessDepartment::where('user_id', $id)->get();
    }

    public function getPermissions($id = null) {
        $id = ($id == null ? Auth::id() : $id);
        return UserPermission::where('user_id', $id)->get();
    }
}
