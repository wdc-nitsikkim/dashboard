<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Profile;

class Position extends Model
{
    protected $table = 'positions';
    public $timestamps = false;

    /**
     * Attributes that are mass-assignable
     */
    protected $fillable = [
        'profile_id', 'position', 'mobile', 'email'
    ];

    /**
     * One-to-one relationship on profiles table
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id')->withDefault();
    }
}
