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

Route::view('/test', 'layouts.admin');
Route::view('/login', 'login');
Route::view('/register', 'register');
Route::view('/notice', 'notifications-view');
Route::get('/homepage-notifications', 'HomepageNotificationController@show');

Route::get('/version', function() {
    return "Laravel v" . Illuminate\Foundation\Application::VERSION . " working on PHP v" . \phpversion();
});
