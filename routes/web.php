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

Route::post('/webhook/encoding', 'Attachment\EncodingWebhookController@handle'); // ./ngrok http -subdomain=jcp 8000

// Jockey
Route::get('/profile', 'Jockey\ProfileController@index')->name('jockey.profile.index');
Route::get('/profile/edit', 'Jockey\ProfileController@edit')->name('jockey.profile.edit');
Route::put('/profile/edit', 'Jockey\ProfileController@update')->name('jockey.profile.update');

Route::get('/activity/list', 'Jockey\ActivityController@index')->name('jockey.activity-list');

Route::get('/activity/{activity}', 'Jockey\ActivityController@show')->name('jockey.activity.show');



// Comments
Route::post('/comment/{comment}', 'Comment\CommentController@update');
Route::delete('/comment/{comment}', 'Comment\CommentController@destroy');

// Activity Comments
Route::get('/activity/{activity}/comment', 'Comment\ActivityCommentController@index')->name('activity.comment.index');
Route::post('/activity/{activity}/comment', 'Comment\ActivityCommentController@store')->name('activity.comment.store');
Route::put('/activity/{activity}/comment/{comment}', 'Comment\ActivityCommentController@update')->name('activity.comment.update');
Route::delete('/activity/{activity}/comment/{comment}', 'Comment\ActivityCommentController@destroy')->name('activity.comment.delete');

Route::get('/dashboard', 'Jockey\DashboardController@index')->name('jockey.dashboard.index');


// Notifications
Route::put('/notification/{notification}/dismiss', 'Notification\NotificationController@dismiss');
Route::post('/notification/{user}/dismiss-all', 'Notification\NotificationController@dismissAll');

// Coach
Route::get('/coach/activity/list', 'Coach\ActivityController@index')->name('coach.activity-list');
Route::post('/coach/activity', 'Coach\ActivityController@store')->name('coach.activity.store');
Route::get('/coach/activity/{activity}', 'Coach\ActivityController@show')->name('coach.activity.show');

Route::get('/coach/dashboard', 'Coach\DashboardController@index')->name('coach.dashboard.index');

Route::get('/coach/auth', 'Coach\TokenAccessController@index')->name('coach.token-access');
Route::get('/coach/profile/password', 'Coach\PasswordController@edit')->name('coach.password.edit');

// Admin
Route::get('/admin/coaches/create', 'Admin\CoachController@create')->name('admin.coach.create');
Route::post('/admin/coaches', 'Admin\CoachController@store')->name('admin.coach.store');
Route::get('/admin/coaches/{coach}', 'Admin\CoachController@show')->name('admin.coach.show');

Route::post('/admin/jockeys/{jockey}/approve', 'Admin\ApprovedJockeyController@create')->name('admin.jockey.approve');

// Change to PendingJockeyController@index or remove and just have in Admin dashboard.
Route::get('/admin/jockeys/pending', 'Admin\JockeyController@pending')->name('admin.jockeys.pending');


// Racing Excellence
Route::get('/admin/racing-excellence/create', 'Admin\RacingExcellenceController@create')->name('admin.racing-excellence.create');
Route::post('/admin/racing-excellence', 'Admin\RacingExcellenceController@store')->name('admin.racing-excellence.store');
Route::get('/admin/racing-excellence/{racingExcellence}', 'Admin\RacingExcellenceController@show')->name('admin.racing-excellence.show');

Route::get('/racing-excellence/{racingExcellence}/results', 'RacingExcellence\RacingExcellenceResultController@create')->name('racing-excellence.results.create');
Route::put('/racing-excellence/participant/{participant}', 'RacingExcellence\ParticipantResultController@update');



// Competency Assessment - Coach
Route::get('/competency-assessment/{competencyAssessment}', 'CompetencyAssessment\CompetencyAssessmentController@show')->name('competency-assessment.show');
Route::post('/coach/competency-assessment', 'CompetencyAssessment\CompetencyAssessmentController@store')->name('competency-assessment.store');
Route::get('/competency-assessment/{competencyAssessment}/edit', 'CompetencyAssessment\CompetencyAssessmentController@edit')->name('competency-assessment.edit');
Route::put('/competency-assessment/{competencyAssessment}/update', 'CompetencyAssessment\CompetencyAssessmentController@update')->name('competency-assessment.update');

// Documents
Route::get('/admin/documents/create', 'Admin\DocumentController@create')->name('admin.document.create');
Route::post('/admin/documents', 'Admin\DocumentController@store')->name('admin.document.store');
Route::put('/admin/documents/{document}', 'Admin\DocumentController@update')->name('admin.document.update');
Route::delete('/admin/documents/{document}', 'Admin\DocumentController@destroy')->name('admin.document.delete');
Route::get('/documents', 'Document\DocumentController@index')->name('documents.index'); // admin made need own index where they can click to edit the documents.

// Messages
Route::get('/messages', 'Message\MessageController@index')->name('messages.index');
Route::post('/messages', 'Message\MessageController@store')->name('message.store');

// Attachments
Route::post('/attachment', 'Attachment\AttachmentController@store');

// Change Password
Route::get('/profile/password', 'Auth\PasswordController@edit')->name('profile.password.edit');
Route::put('/profile/password', 'Auth\PasswordController@update')->name('profile.password.update');