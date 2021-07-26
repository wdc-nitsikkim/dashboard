<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageNotification extends Model {
    use softDeletes;

    protected $table = 'homepage_notifications';
    protected $dates = ['deleted_at'];
}
