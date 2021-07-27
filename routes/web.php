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
Route::get('/auth', function() {
    Auth::loginUsingId(1);
    return "Logged in";
});
Route::get('/logout', function() {
    Auth::logout();
    return "Logged out!";
})->name('logout');
Route::get('/hash/{str}', function($str) {
    return \Hash::make($str);
});

Route::name('artisan.')->middleware('checkRole:admin')->group(function() {
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


/* homepage routes */
Route::name('homepage.')->prefix('homepage')->group(function() {
    Route::name('notification.')->prefix('notifications')->namespace('Homepage')
        ->middleware(['auth'])->group(function() {
        Route::get('/', 'NotificationController@show')->name('show');
        Route::get('/add/{type?}', 'NotificationController@add')
            ->where('type', 'announcement|download|notice|tender')->name('add');
        Route::post('/save', 'NotificationController@saveNew')->name('saveNew');
        Route::get('/trashed', 'NotificationController@showTrashed')->name('showTrashed');
        Route::get('/edit/{notification}', 'NotificationController@editPage')->name('editPage');
        Route::post('/update/{notification}', 'NotificationController@update')->name('update');
        Route::get('/change-status/{id}/{status}', 'NotificationController@updateStatus')
            ->where(['id'=> '[0-9]+','status'=> 'enable|disable'])->name('changeStatus');  /* POST */
        Route::get('/restore/{id}', 'NotificationController@restore')
            ->where('id', '[0-9]+')->name('restore');  /* GET, POST */
        Route::get('/soft-delete/{notification}', 'NotificationController@softDelete')->name('softDelete');  /* DELETE */

        Route::get('/delete/{id}', 'NotificationController@delete')
            ->where('id', '[0-9]+')->name('delete'); /* DELETE */
    });
});

/* framewrok version */
Route::get('/version', function() {
    return "Laravel v" . Illuminate\Foundation\Application::VERSION . " working on PHP v" . \phpversion();
});
