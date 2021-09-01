<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Hod;
use App\Models\User;
use App\Models\Batch;
use App\Models\Result;
use App\Models\Student;
use App\Models\Profile;
use App\Models\Subject;
use App\Models\Position;
use App\Models\Department;
use App\Models\StudentInfo;
use App\Models\HomepageNotification;
use App\Policies\HodPolicy;
use App\Policies\UserPolicy;
use App\Policies\BatchPolicy;
use App\Policies\ResultPolicy;
use App\Policies\StudentPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\SubjectPolicy;
use App\Policies\PositionPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\StudentInfoPolicy;
use App\Policies\HomepageNotificationPolicy as NotificationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Hod::class => HodPolicy::class,
        User::class => UserPolicy::class,
        Batch::class => BatchPolicy::class,
        Result::class => ResultPolicy::class,
        Student::class => StudentPolicy::class,
        Profile::class => ProfilePolicy::class,
        Subject::class => SubjectPolicy::class,
        Position::class => PositionPolicy::class,
        Department::class => DepartmentPolicy::class,
        StudentInfo::class => StudentInfoPolicy::class,
        HomepageNotification::class => NotificationPolicy::class,
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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('root') ? true : null;
        });
    }
}
