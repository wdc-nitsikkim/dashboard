<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\GlobalAccessors;

class Feedback extends Model
{
    use GlobalAccessors;

    protected $table = 'feedbacks';

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'rating', 'feedback'];

    /**
     * Defines many-to-one relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
