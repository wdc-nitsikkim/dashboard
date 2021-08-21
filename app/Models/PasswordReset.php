<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class PasswordReset extends Model {
    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['email', 'token', 'created_at'];

    public function user() {
        $this->belongsTo(User::class, 'email')->withDefault();
    }
}
