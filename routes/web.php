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

/* view routes */
Route::view('/default', 'layouts.admin');
Route::view('/login', 'login')->middleware('guest')->name('login');
Route::view('/register', 'register')->middleware('guest')->name('register');

/* test routes */
Route::get('/auth/{id?}', function($id = 1) {
    Auth::loginUsingId($id);
    return "Logged in";
});
Route::get('/logout', function() {
    Auth::logout();
    session()->flush();
    return "Logged out!";
})->name('logout');
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

/* root routes */
Route::name('root.')->group(function() {
    Route::post('/clear-session', 'RootController@clearSession')->name('clearSession');
});

/* homepage routes */
Route::name('homepage.')->prefix('homepage')->middleware(['auth'])->group(function() {
    /* notification routes */
    Route::name('notification.')->prefix('notifications')->namespace('Homepage')
        ->group(function() {

        Route::get('/', 'NotificationController@show')->name('show');
        Route::get('/add/{type?}', 'NotificationController@add')
            ->where('type', 'announcement|download|notice|tender')->name('add');
        Route::post('/save', 'NotificationController@saveNew')->name('saveNew');
        Route::get('/trashed', 'NotificationController@showTrashed')->name('showTrashed');
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
Route::name('department.')->prefix('department')->middleware(['auth'])
    ->namespace('Department')->group(function() {

    /* department home routes */
    Route::get('/', 'MainController@index')->name('index');
    Route::get('/select', 'MainController@select')->name('select');
    Route::post('/save-in-session/{dept}', 'MainController@saveInSession')->name('saveInSession');
    Route::get('/test', 'MainController@test');
    Route::get('/{dept}', 'MainController@home')->name('home');

    /* student routes */
    Route::name('students.')->prefix('{dept}/students')->group(function() {
        Route::get('/', 'StudentController@selectBatch')->name('selectBatch');
        Route::get('/test/{student?}', 'StudentController@test');

        Route::prefix('{batch}')->group(function() {
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
});

/* framewrok version */
Route::get('/version', function() {
    return "Laravel v" . Illuminate\Foundation\Application::VERSION . " working on PHP v" . \phpversion();
});
