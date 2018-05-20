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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Jockey
Route::get('/profile', 'Jockey\ProfileController@index')->name('jockey.profile.index');
Route::get('/profile/edit', 'Jockey\ProfileController@edit')->name('jockey.profile.edit');
Route::patch('/profile/edit', 'Jockey\ProfileController@update')->name('jockey.profile.update');


// Coach
Route::post('/coach/activity', 'Coach\ActivityController@store')->name('coach.activity.store');
Route::get('/coach/activity/{activity}', 'Coach\ActivityController@show')->name('coach.activity.show');


// Admin
Route::post('/admin/coaches', 'Admin\CoachController@store')->name('admin.coach.store');
Route::get('/admin/coaches/{coach}', 'Admin\CoachController@show')->name('admin.coach.show');