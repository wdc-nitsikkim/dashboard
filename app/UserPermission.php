<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model {
    protected $table = 'user_permissions';

    public function user() {
        return $this->belongsTo(\App\User::class)->withDefault();
    }
}
