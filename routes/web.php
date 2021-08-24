<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* redirect routes */
Route::redirect('/home', '/', 301);

Route::get('/hash/{str}', function ($str) {
    return \Hash::make($str);
});

/* app settings routes */
Route::name('myApp.')->prefix('app')->middleware('auth')->group(function () {
    Route::get('/settings', 'MyAppController@siteSettings')->name('settings');
    Route::post('/backup/db/create', 'MyAppController@dbBackupCreate')->name('dbBackupCreate');
    Route::post('/backup/remove-dir', 'MyAppController@removeBackupDir')->name('removeBackupDir');
    Route::post('/execute/{command}', 'MyAppController@executeArtisanCommand')
        ->name('artisan.command');
});

/* auth routes */
Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::get('/register/{role?}', 'Auth\RegisterController@index')->name('register');

    Route::name('auth.')->prefix('auth')->namespace('Auth')->group(function () {
        Route::post('/signin/default', 'LoginController@defaultLogin')->name('signin.default');
        Route::post('/signin/google', 'LoginController@withGoogle')->name('signin.withgoogle');

        Route::post('/signup/default', 'RegisterController@defaultSignup')->name('signup.default');

        Route::view('/forgot-password', 'auth.forgotPassword')->name('forgotPassword');
        Route::post('/forgot-password', 'ForgotPasswordController@sendEmail');
        Route::get('/reset-password/{email}/{token}', 'ResetPasswordController@show');
        Route::post('/reset-password/{email}/{token}', 'ResetPasswordController@reset')
            ->name('resetPassword');

        Route::get('/test', 'RegisterController@test');
    });
});

/* logout */
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

/* root routes */
Route::name('root.')->middleware('auth')->group(function () {
    Route::view('/default', 'layouts.admin')->name('default');
    Route::view('/lock', 'lockscreen')->name('lockscreen');
    Route::post('/lock', 'Auth\LoginController@confirmPassword')->name('confirmPassword');

    Route::post('/clear-session', 'RootController@clearSession')->name('clearSession');
});

/* user account routes */
Route::name('users.')->prefix('users')->middleware('auth')->group(function () {
    Route::name('verifyEmail.')->prefix('email')->group(function () {
        Route::view('/verify', 'auth.verifyEmail')->name('view');
        Route::post('/send-code', 'UserController@sendVerificationEmail')
            ->name('sendMail');
        Route::get('/verify/{token}', 'UserController@confirmEmail')->name('confirm');
    });

    Route::middleware('email.verified')->group(function () {
        Route::get('/', 'UserController@show')->name('show');
        Route::get('/search', 'UserController@searchForm')->name('searchForm');
        Route::get('/search/results', 'UserController@search')->name('search');

        Route::name('manage.')->prefix('manage')->middleware('password.confirm')
            ->group(function () {

            Route::get('/{id}', 'ManageUserController@manage')->name('page');
            Route::post('/save-permissions/{id}', 'ManageUserController@savePermissions')
                ->name('savePermissions');
            Route::post('/grant-role/{id}', 'ManageUserController@grantRole')
                ->name('grantRole');
            Route::delete('/revoke-role/{role_id}', 'ManageUserController@revokeRole')
                ->name('revokeRole');
            Route::post('/grant-department-access/{id}', 'ManageUserController@grantDepartmentAccess')
                ->name('grantDeptAccess');
            Route::post('/grant-subject-access/{id}', 'ManageUserController@grantSubjectAccess')
                ->name('grantSubAccess');
            Route::delete('/revoke-subject-access/{user_id}/{subject_id}',
                'ManageUserController@revokeSubjectAccess')->name('revokeSubAccess');
            Route::delete('/revoke-department-access/{user_id}/{dept_id}',
                'ManageUserController@revokeDepartmentAccess')->name('revokeDeptAccess');
        });
    });

    Route::get('/{id}', 'UserController@profile')->name('account');
    Route::post('/update/{id}', 'UserController@update')->name('update');
    Route::post('/change-password/{id}', 'UserController@changePassword')->name('changePassword');
    Route::delete('/soft-delete/{id}', 'UserController@softDelete')
        ->middleware('password.confirm')->name('softDelete');
    Route::post('/restore/{id}', 'UserController@restore')->name('restore');
    Route::delete('/delete/{id}', 'UserController@delete')
        ->middleware('password.confirm')->name('delete');

    Route::get('/test', 'UserController@test');
});

/* feedback routes */
Route::name('feedbacks.')->prefix('feedback')->middleware(['auth', 'email.verified'])->group(function () {
    Route::post('/save', 'FeedbackController@saveNew')->name('saveNew');
});

/* admin routes --> all roles except student */
Route::namespace('Admin')->name('admin.')->prefix('admin')->middleware(['auth', 'email.verified'])->group(function () {
    /* office routes */
    Route::name('office.')->prefix('office')->group(function () {
        Route::name('hods.')->prefix('hods')->group(function () {
            Route::get('/', 'HodController@show')->name('show');
            Route::post('/assign', 'HodController@assign')->name('assign');
            Route::delete('/remove/{dept_id}', 'HodController@remove')
                ->middleware('password.confirm')->name('remove');
        });

        Route::name('positions.')->prefix('positions')->group(function () {
            Route::get('/', 'PositionController@show')->name('show');
            Route::post('/assign', 'PositionController@assign')->name('assign');
            Route::delete('/remove/{id}', 'PositionController@remove')->name('remove');
        });
    });

    /* homepage routes */
    Route::name('homepage.')->prefix('homepage')->group(function () {
        /* notification routes */
        Route::name('notification.')->prefix('notifications')->group(function () {
            Route::get('/{trashed?}', 'NotificationController@show')
                ->where('trashed', 'trashed')->name('show');
            Route::get('/search', 'NotificationController@searchForm')->name('searchForm');
            Route::get('/search/results', 'NotificationController@search')->name('search');
            Route::get('/add/{type?}', 'NotificationController@add')
                ->where('type', 'announcement|download|notice|tender')->name('add');
            Route::post('/save', 'NotificationController@saveNew')->name('saveNew');
            Route::get('/edit/{notification}', 'NotificationController@edit')->name('edit');
            Route::post('/update/{notification}', 'NotificationController@update')->name('update');
            Route::post('/change-status/{id}/{status}', 'NotificationController@updateStatus')
                ->where('status', 'enable|disable')->name('changeStatus');
            Route::delete('/soft-delete/{notification}', 'NotificationController@softDelete')->name('softDelete');
            Route::post('/restore/{id}', 'NotificationController@restore')->name('restore');
            Route::delete('/delete/{id}', 'NotificationController@delete')
                ->middleware('password.confirm')->name('delete');

            Route::get('/test', 'NotificationController@test');
        });
    });

    /* department routes */
    Route::name('department.')->prefix('departments')->group(function () {
        Route::get('/', 'DepartmentController@show')->name('show');
        Route::get('/index', 'DepartmentController@index')->name('index');
        Route::get('/select', 'DepartmentController@select')->name('select');
        Route::post('/save-in-session/{dept}', 'DepartmentController@saveInSession')->name('saveInSession');

        Route::get('/test', 'DepartmentController@test');

        Route::get('/add', 'DepartmentController@add')->name('add');
        Route::post('/save', 'DepartmentController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'DepartmentController@edit')->name('edit');
        Route::post('/update/{id}', 'DepartmentController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'DepartmentController@softDelete')
            ->middleware('password.confirm')->name('softDelete');
        Route::post('/restore/{id}', 'DepartmentController@restore')->name('restore');
        Route::delete('/delete/{id}', 'DepartmentController@delete')
            ->middleware('password.confirm')->name('delete');

        Route::prefix('{dept}')->group(function () {
            Route::get('/', 'DepartmentController@home')->name('home');
            Route::get('/order-people', 'DepartmentController@orderPeople')->name('orderPeople');
            Route::post('/save-order', 'DepartmentController@saveOrder')->name('saveOrder');
        });
    });

    /* profile routes */
    Route::name('profiles.')->prefix('profiles')->group(function () {
        Route::get('/', 'ProfileController@show')->name('show');
        Route::get('/trashed', 'ProfileController@showTrashed')->name('showTrashed');
        Route::get('/search', 'ProfileController@searchForm')->name('searchForm');
        Route::get('/search/results', 'ProfileController@search')->name('search');
        Route::get('/add', 'ProfileController@add')->name('add');
        Route::post('/save', 'ProfileController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'ProfileController@edit')->name('edit');
        Route::post('/link', 'ProfileController@link')
            ->middleware('password.confirm')->name('link');
        Route::post('/unlink', 'ProfileController@unlink')
            ->middleware('password.confirm')->name('unlink');
        Route::post('/update/{id}', 'ProfileController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'ProfileController@softDelete')
            ->middleware('password.confirm')->name('softDelete');
        Route::post('/restore/{id}', 'ProfileController@restore')->name('restore');
        Route::delete('/delete/{id}', 'ProfileController@delete')
            ->middleware('password.confirm')->name('delete');

        Route::get('/test', 'ProfileController@test');
    });

    /* student routes */
    Route::name('students.')->prefix('students')->group(function () {
        Route::get('/', 'StudentController@handleRedirect')->name('handleRedirect');
        Route::get('/search', 'StudentController@searchForm')->name('searchForm');
        Route::get('/search/results', 'StudentController@search')->name('search');

        Route::get('/test', 'StudentController@test');

        Route::prefix('{dept}/{batch}')->group(function () {
            Route::get('/', 'StudentController@show')->name('show');
            Route::get('/add', 'StudentController@add')->name('add');
            Route::get('/bulk-insert', 'StudentController@bulkInsert')->name('bulkInsert');
            Route::post('/bulk-insert', 'StudentController@bulkInsertSave')->name('bulkInsertSave');
            Route::post('/save', 'StudentController@saveNew')->name('saveNew');
            Route::get('/edit/{student}', 'StudentController@edit')->name('edit');
            Route::post('/update/{student}', 'StudentController@update')->name('update');
            Route::delete('/soft-delete/{student}', 'StudentController@softDelete')
                ->middleware('password.confirm')->name('softDelete');
            Route::post('/restore/{id}', 'StudentController@restore')->name('restore');
            Route::delete('/delete/{id}', 'StudentController@delete')
                ->middleware('password.confirm')->name('delete');
        });
    });

    /* batch routes */
    Route::name('batch.')->prefix('batches')->group(function () {
        Route::get('/', 'BatchController@show')->name('show');
        Route::get('/select', 'BatchController@select')->name('select');
        Route::post('/save-in-session/{batch}', 'BatchController@saveInSession')->name('saveInSession');

        Route::get('/test', 'BatchController@test')->name('test');

        Route::get('/add', 'BatchController@add')->name('add');
        Route::post('/save', 'BatchController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'BatchController@edit')->name('edit');
        Route::post('/update/{id}', 'BatchController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'BatchController@softDelete')
            ->middleware('password.confirm')->name('softDelete');
        Route::post('/restore/{id}', 'BatchController@restore')->name('restore');
        Route::delete('/delete/{id}', 'BatchController@delete')
            ->middleware('password.confirm')->name('delete');
    });

    /* subject routes */
    Route::name('subjects.')->prefix('subjects')->group(function () {
        Route::get('/', 'SubjectController@show')->name('show');
        Route::get('/select', 'SubjectController@select')->name('select');
        Route::post('/save-in-session/{subject}', 'SubjectController@saveInSession')->name('saveInSession');

        Route::get('/test', 'SubjectController@test');
    });

    /* result routes */
    Route::name('results.')->prefix('results')->group(function () {
        Route::get('/', 'ResultController@handleRedirect')->name('handleRedirect');
        Route::get('/semwise', 'ResultController@semWiseHandleRedirect')
            ->name('semWiseHandleRedirect');
        Route::get('/semwise/{dept}/{batch}/{result_type?}/{semester?}',
            'ResultController@showSemWise')->name('showSemWise');

        Route::get('/test', 'ResultController@test');

        Route::prefix('{dept}/{batch}/{subject}')->group(function () {
            Route::get('/{result_type?}', 'ResultController@show')->name('show');
            Route::post('/{result_type}/save', 'ResultController@save')->name('save')
                ->middleware('password.confirm');
        });
    });
});

/* framework version */
Route::get('/version', function () {
    return "Laravel v" . Illuminate\Foundation\Application::VERSION . " working on PHP v" . \phpversion();
});
