<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccessDepartment extends Model {
    protected $table = 'user_access_departments';

    public function user() {
        return $this->belongsTo(\App\User::class)->withDefault();
    }
}
