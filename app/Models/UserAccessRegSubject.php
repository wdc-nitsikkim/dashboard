<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\RegisteredSubject;
use App\Traits\GlobalAccessors;

class UserAccessRegSubject extends Model
{
    use GlobalAccessors;

    protected $table = 'user_access_reg_subjects';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'registered_subject_id', 'created_at'];

    /**
     * Defines many-to-one relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function registeredSubject()
    {
        return $this->belongsTo(RegisteredSubject::class, 'registered_subject_id')->withDefault();
    }
}
