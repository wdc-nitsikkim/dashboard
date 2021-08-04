<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Profile;

class UserProfileLink extends Model {
    protected $table = 'user_profile_links';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'profile_id'];

    /**
     * one-to-one relation on Users table
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * one-to-one relation on Profiles table
     */
    public function profile() {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
