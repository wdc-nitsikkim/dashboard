<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')->group(function () {
    Route::get('/search-users', 'PublicApiController@searchUsersByName')
        ->name('searchUsersByName');
    Route::get('/search-profiles', 'PublicApiController@searchProfilesByName')
        ->name('searchProfilesByName');
    Route::get('/search-subjects', 'PublicApiController@searchSubject')->name('searchSubject');
});
