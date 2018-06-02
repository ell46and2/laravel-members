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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


// Jockey
Route::get('/profile', 'Jockey\ProfileController@index')->name('jockey.profile.index');
Route::get('/profile/edit', 'Jockey\ProfileController@edit')->name('jockey.profile.edit');
Route::put('/profile/edit', 'Jockey\ProfileController@update')->name('jockey.profile.update');

Route::get('/activity/{activity}', 'Jockey\ActivityController@show')->name('jockey.activity.show');

// Comment
Route::post('/activity/{activity}/comment', 'Comment\CommentController@store')->name('comment.store');


// Coach
Route::post('/coach/activity', 'Coach\ActivityController@store')->name('coach.activity.store');
Route::get('/coach/activity/{activity}', 'Coach\ActivityController@show')->name('coach.activity.show');


// Admin
Route::post('/admin/coaches', 'Admin\CoachController@store')->name('admin.coach.store');
Route::get('/admin/coaches/{coach}', 'Admin\CoachController@show')->name('admin.coach.show');

Route::post('/admin/racing-excellence', 'Admin\RacingExcellenceController@store')->name('admin.racing-excellence.store');