<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageNotification extends Model {
    use softDeletes;

    protected $table = 'homepage_notifications';
    protected $dates = ['deleted_at'];

    /**
     * attributes that cannot be mass-assigned
     *
     * @var array
     */
    protected $guarded = ['id'];
}
