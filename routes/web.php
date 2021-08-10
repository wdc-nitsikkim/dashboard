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

/* artisan routes */
Route::name('artisan.')->middleware('auth')->group(function() {
    Route::get('/link-storage', function() {
        $exit_code = Artisan::call('storage:link');
        return "Storage linked!";
    })->name('linkStorage');

    Route::get('/clear-route-cache', function() {
        $exit_code = Artisan::call('cache:clear');
        return "Route cache cleared!";
    })->name('clearCache');

    Route::get('/link-storage-server', function () {
        /* !!! for server use only !!! */
        /* actual storage path */
        $target = '/home/ntskm85i/domains/nitsikkim.ac.in/laravel-5/storage/app/public';
        /* public storage path */
        $shortcut = '/home/ntskm85i/domains/nitsikkim.ac.in/public_html/admin-dashboard-laravel/storage';
        echo symlink($target, $shortcut) ? 'Linked' : 'Failed';
        //echo $_SERVER['DOCUMENT_ROOT'];
    });
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
Route::name('user.')->prefix('users')->middleware('auth')->group(function() {
    Route::get('/', 'UserController@show')->name('show');

    Route::prefix('profile/{id}')->group(function () {
        Route::get('/', 'UserController@profile')->name('profile');
        Route::post('/update', 'UserController@update')->name('update');
        Route::post('/change-password', 'UserController@changePassword')->name('changePassword');
    });

    Route::get('/test', 'UserController@test')->name('show');
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
            Route::post('/restore/{id}', 'NotificationController@restore')->name('restore');
            Route::delete('/soft-delete/{notification}', 'NotificationController@softDelete')->name('softDelete');

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
