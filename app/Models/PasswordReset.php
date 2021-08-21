<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Traits\GlobalAccessors;

class PasswordReset extends Model {
    use GlobalAccessors;

    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['email', 'token', 'created_at'];

    public function user() {
        $this->belongsTo(User::class, 'email')->withDefault();
    }
}
