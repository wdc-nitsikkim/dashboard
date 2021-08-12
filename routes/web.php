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

use App\CustomHelper;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

/* redirect routes */
Route::redirect('/home', '/', 301);

Route::get('/hash/{str}', function($str) {
    return \Hash::make($str);
});

/* site-settings routes */
Route::middleware('auth')->group(function() {
    Route::get('/site-settings', 'RootController@siteSettings')->name('siteSettings');
    Route::post('/execute/{command}', 'RootController@executeArtisanCommand')
        ->name('artisan.command');
});

/* auth routes */
Route::middleware('guest')->group(function() {
    Route::view('/login', 'login')->name('login');
    Route::get('/register/{role?}', 'Auth\RegisterController@index')->name('register');

    Route::name('auth.')->prefix('auth')->namespace('Auth')->group(function() {
        Route::post('/signin/default', 'LoginController@defaultLogin')->name('signin.default');
        Route::post('/signin/google', 'LoginController@withGoogle')->name('signin.withgoogle');

        Route::post('/signup/default', 'RegisterController@defaultSignup')->name('signup.default');

        Route::get('/test', 'RegisterController@test');
    });
});

/* logout */
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

/* root routes */
Route::name('root.')->middleware('auth')->group(function() {
    Route::view('/default', 'layouts.admin')->name('default');
    Route::post('/clear-session', 'RootController@clearSession')->name('clearSession');
    Route::get('/test', 'RootController@test');
});

/* user account routes */
Route::name('users.')->prefix('users')->middleware('auth')->group(function() {
    Route::get('/', 'UserController@show')->name('show');
    Route::get('/search', 'UserController@searchForm')->name('searchForm');
    Route::get('/search/results', 'UserController@search')->name('search');

    Route::name('manage.')->prefix('manage')->group(function() {
        Route::get('/{id}', 'ManageUserController@manage')->name('page');
        Route::post('/save-permissions/{id}', 'ManageUserController@savePermissions')
            ->name('savePermissions');
        Route::post('/grant-role/{id}', 'ManageUserController@grantRole')
            ->name('grantRole');
        Route::delete('/revoke-role/{role_id}', 'ManageUserController@revokeRole')
            ->name('revokeRole');
        Route::post('/grant-department-access/{id}', 'ManageUserController@grantDepartmentAccess')
            ->name('grantDeptAccess');
        Route::delete('/revoke-department-access/{user_id}/{dept_id}',
            'ManageUserController@revokeDepartmentAccess')->name('revokeDeptAccess');
    });

    Route::get('/{id}', 'UserController@profile')->name('account');
    Route::post('/update/{id}', 'UserController@update')->name('update');
    Route::post('/change-password/{id}', 'UserController@changePassword')->name('changePassword');
    Route::delete('/soft-delete/{id}', 'UserController@softDelete')->name('softDelete');
    Route::post('/restore/{id}', 'UserController@restore')->name('restore');
    Route::delete('/delete/{id}', 'UserController@delete')->name('delete');

    Route::get('/test', 'UserController@test');
});

/* admin routes --> all roles except student */
Route::namespace('Admin')->name('admin.')->prefix('admin')->middleware(['auth'])->group(function() {
    /* homepage routes */
    Route::name('homepage.')->prefix('homepage')->group(function() {
        /* notification routes */
        Route::name('notification.')->prefix('notifications')->group(function() {
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
            Route::delete('/delete/{id}', 'NotificationController@delete')->name('delete');

            Route::get('/test', 'NotificationController@test');
        });
    });

    /* department routes */
    Route::name('department.')->prefix('departments')->group(function() {
        Route::get('/', 'DepartmentController@show')->name('show');
        Route::get('/index', 'DepartmentController@index')->name('index');
        Route::get('/select', 'DepartmentController@select')->name('select');
        Route::post('/save-in-session/{dept}', 'DepartmentController@saveInSession')->name('saveInSession');

        Route::get('/test', 'DepartmentController@test');

        Route::get('/add', 'DepartmentController@add')->name('add');
        Route::post('/save', 'DepartmentController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'DepartmentController@edit')->name('edit');
        Route::post('/update/{id}', 'DepartmentController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'DepartmentController@softDelete')->name('softDelete');
        Route::post('/restore/{id}', 'DepartmentController@restore')->name('restore');
        Route::delete('/delete/{id}', 'DepartmentController@delete')->name('delete');

        Route::get('/{dept}', 'DepartmentController@home')->name('home');
    });

    /* profile routes */
    Route::name('profiles.')->prefix('profiles')->group(function() {
        Route::get('/', 'ProfileController@show')->name('show');
        Route::get('/trashed', 'ProfileController@showTrashed')->name('showTrashed');
        Route::get('/search', 'ProfileController@searchForm')->name('searchForm');
        Route::get('/search/results', 'ProfileController@search')->name('search');
        Route::get('/add', 'ProfileController@add')->name('add');
        Route::post('/save', 'ProfileController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'ProfileController@edit')->name('edit');
        Route::post('/link/{user_id}/{profile_id}', 'ProfileController@link')->name('link');
        Route::post('/unlink/{user_id}/{profile_id}', 'ProfileController@unlink')->name('unlink');
        Route::post('/update/{id}', 'ProfileController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'ProfileController@softDelete')->name('softDelete');
        Route::post('/restore/{id}', 'ProfileController@restore')->name('restore');
        Route::delete('/delete/{id}', 'ProfileController@delete')->name('delete');

        Route::get('/test', 'ProfileController@test');
    });

    /* student routes */
    Route::name('students.')->prefix('students')->group(function() {
        Route::get('/', 'StudentController@handleRedirect')->name('handleRedirect');
        Route::get('/search', 'StudentController@searchForm')->name('searchForm');
        Route::get('/search/results', 'StudentController@search')->name('search');

        Route::get('/test', 'StudentController@test');

        Route::prefix('{dept}/{batch}')->group(function() {
            Route::get('/', 'StudentController@show')->name('show');
            Route::get('/add', 'StudentController@add')->name('add');
            Route::post('/save', 'StudentController@saveNew')->name('saveNew');
            Route::get('/edit/{student}', 'StudentController@edit')->name('edit');
            Route::post('/update/{student}', 'StudentController@update')->name('update');
            Route::delete('/soft-delete/{student}', 'StudentController@softDelete')->name('softDelete');
            Route::post('/restore/{id}', 'StudentController@restore')->name('restore');
            Route::delete('/delete/{id}', 'StudentController@delete')->name('delete');
        });
    });

    /* batch routes */
    Route::name('batch.')->prefix('batches')->group(function() {
        Route::get('/', 'BatchController@show')->name('show');
        Route::get('/select', 'BatchController@select')->name('select');
        Route::post('/save-in-session/{batch}', 'BatchController@saveInSession')->name('saveInSession');

        Route::get('/test', 'BatchController@test')->name('test');

        Route::get('/add', 'BatchController@add')->name('add');
        Route::post('/save', 'BatchController@saveNew')->name('saveNew');
        Route::get('/edit/{id}', 'BatchController@edit')->name('edit');
        Route::post('/update/{id}', 'BatchController@update')->name('update');
        Route::delete('/soft-delete/{id}', 'BatchController@softDelete')->name('softDelete');
        Route::post('/restore/{id}', 'BatchController@restore')->name('restore');
        Route::delete('/delete/{id}', 'BatchController@delete')->name('delete');
    });
});

/* framework version */
Route::get('/version', function() {
    return "Laravel v" . Illuminate\Foundation\Application::VERSION . " working on PHP v" . \phpversion();
});
