<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class UserPermission extends Model {
    protected $table = 'user_permissions';

    public function user() {
        return $this->belongsTo(User::class)->withDefault();
    }
}
