<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\User;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Profile;
use App\Models\Department;
use App\Models\HomepageNotification;
use App\Policies\UserPolicy;
use App\Policies\BatchPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\StudentPolicy;
use App\Policies\HomepageNotificationPolicy as NotificationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        HomepageNotification::class => NotificationPolicy::class,
        Department::class => DepartmentPolicy::class,
        Profile::class => ProfilePolicy::class,
        Student::class => StudentPolicy::class,
        Batch::class => BatchPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* all actions allowed for 'root' user */
        Gate::before(function($user, $ability) {
            return $user->hasRole('root') ? true : null;
        });
    }
}
