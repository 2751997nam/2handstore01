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

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => '/register', 'as' => 'register.'], function()
{
    Route::get('/verify/{verify_token}', 'Auth\RegisterController@verify')->name('verify');
    Route::get('/resendEmail', 'Auth\RegisterController@showResendForm')->name('showResendForm');
    Route::post('/resendEmail', 'Auth\RegisterController@resendEmail')->name('resendEmail');
});

Auth::routes();
