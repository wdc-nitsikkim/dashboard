<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model {
    protected $table = 'user_permissions';

    public function user() {
        return $this->belongsTo(\App\Models\User::class)->withDefault();
    }
}
