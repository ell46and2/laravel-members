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


Route::group(['middleware' => ['auth','role:coach,admin']], function() {
	// Invoice
	Route::post('/invoices/{invoice}/invoice-lines', 'Invoice\InvoiceController@addLines')->name('invoice.add-lines');
	Route::delete('/invoices/{invoice}/invoice-lines/{invoiceLine}', 'Invoice\InvoiceController@removeLine')->name('invoice.remove-line');
	Route::get('/invoices/invoice/{invoice}/add', 'Invoice\InvoiceController@add')->name('invoice.add');
	Route::get('/invoices/{coach}', 'Invoice\InvoiceController@index')->name('invoice.index');
	Route::post('/invoices/{coach}', 'Invoice\InvoiceController@store')->name('invoice.store');
	Route::get('/invoices/invoice/{invoice}', 'Invoice\InvoiceController@show')->name('invoice.show');
	Route::post('/invoices/invoice/{invoice}/submit', 'Invoice\InvoiceController@submit')->name('invoice.submit-review');
	Route::post('/invoices/invoice/{invoice}/approve', 'Invoice\InvoiceController@approve')->name('invoice.approve');
	
	Route::delete('/invoices/{invoice}/misc/{invoiceLine}', 'Invoice\MiscController@destroy')->name('invoice.misc.delete');
	Route::get('/invoice/{invoice}/misc/add', 'Invoice\MiscController@create')->name('invoice.misc.create');
	Route::post('/invoices/invoice/{invoice}/misc', 'Invoice\MiscController@store')->name('invoice.misc.store');
	Route::get('/invoices/invoice/{invoice}/misc/{invoiceLine}', 'Invoice\MiscController@edit')->name('invoice.misc.edit');
	Route::put('/invoices/invoice/{invoice}/misc/{invoiceLine}', 'Invoice\MiscController@update')->name('invoice.misc.update');

	Route::get('/invoices/invoice/{invoice}/mileage/add', 'Invoice\MileageController@create')->name('invoice.mileage.add');
	Route::post('/invoices/invoice/{invoice}/mileage', 'Invoice\MileageController@store')->name('invoice.mileage.store');
	Route::get('/invoices/invoice/{invoice}/mileage/{mileage}', 'Invoice\MileageController@edit')->name('invoice.mileage.edit');
	Route::put('/invoices/invoice/{invoice}/mileage/{mileage}', 'Invoice\MileageController@update')->name('invoice.mileage.update');
	Route::delete('/invoices/invoice/{invoice}/mileage/{mileage}', 'Invoice\MileageController@destroy')->name('invoice.mileage.delete');
});

Route::get('/coach/dashboard', 'Coach\DashboardController@index')->name('coach.dashboard.index');

Route::get('/coach/auth', 'Coach\TokenAccessController@index')->name('coach.token-access');
Route::post('/coach/auth', 'Coach\TokenAccessController@update')->name('coach.token-access.update');
Route::get('/coach/profile/password', 'Coach\PasswordController@edit')->name('coach.password.edit');

//
Route::get('/jockey/{jockey}', 'Jockey\JockeyController@show')->name('jockey.show');
Route::put('/jockey/{jockey}/edit', 'Jockey\JockeyController@updateProfile')->name('jockey.update');
Route::put('/jockey/{jockey}/status', 'Jockey\JockeyController@updateStatus')->name('jockey.status.update');
Route::post('/jockey/{jockey}/setApi', 'Jockey\JockeyController@setApi')->name('jockey.set-api');


Route::get('/coach/{coach}', 'Coach\CoachController@show')->name('coach.show');
Route::put('/coach/{coach}/edit', 'Coach\CoachController@updateProfile')->name('coach.update');
Route::put('/coach/{coach}/status', 'Coach\CoachController@updateStatus')->name('coach.status.update');


Route::get('/jets/{jet}', 'Jet\JetController@show')->name('jet.show');
Route::put('/jets/{jet}/edit', 'Jet\JetController@updateProfile')->name('jet.update');
Route::put('/jets/{jet}/status', 'Jet\JetController@updateStatus')->name('jet.status.update');


// Jockey
Route::get('/profile', 'Jockey\ProfileController@index')->name('jockey.profile.index');
Route::get('/profile/edit', 'Jockey\ProfileController@edit')->name('jockey.profile.edit');
Route::put('/profile/edit', 'Jockey\ProfileController@update')->name('jockey.profile.update');

Route::get('/activity/log', 'Jockey\ActivityController@index')->name('jockey.activity-log');

Route::get('/activity/{activity}', 'Jockey\ActivityController@show')->name('jockey.activity.show');

//Coach Assign/unassign jockeys - (Admin only)
Route::post('/coach/{coach}/assign-jockey', 'Coach\AssignJockeyController@create');
Route::post('/coach/{coach}/unassign-jockey', 'Coach\AssignJockeyController@destroy');

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

// Activity
Route::delete('/activity/{activity}', 'Activity\ActivityController@destroy')->name('activity.delete');

// Coach
Route::get('/coach/activity/log', 'Coach\ActivityController@index')->name('coach.activity-log');
Route::post('/coach/activity', 'Coach\ActivityController@store')->name('coach.activity.store');
Route::get('/coach/activity/create', 'Coach\ActivityController@singleCreate')->name('coach.1:1-activity.create');
Route::get('/coach/activity/{activity}/edit', 'Coach\ActivityController@edit')->name('coach.activity.edit');
Route::put('/coach/activity/{activity}/single', 'Coach\ActivityController@singleUpdate')->name('coach.1:1-activity.update');
Route::put('/coach/activity/{activity}/group', 'Coach\ActivityController@groupUpdate')->name('coach.group-activity.update');
Route::get('/coach/activity/group-create', 'Coach\ActivityController@groupCreate')->name('coach.group-activity.create');
Route::post('/activity/{activity}/feedback/{jockey}', 'Coach\ActivityJockeyFeedbackController@create');
Route::get('/coach/activity/{activity}', 'Coach\ActivityController@show')->name('coach.activity.show');


Route::get('/coach/jockeys', 'Coach\JockeyController@index')->name('coach.jockeys.index');



// Admin
Route::get('/admin/coaches/create', 'Admin\CoachController@create')->name('admin.coach.create');
Route::post('/admin/coaches', 'Admin\CoachController@store')->name('admin.coach.store');


Route::get('/admin/jets/create', 'Admin\JetController@create')->name('admin.jet.create');
Route::post('/admin/jets', 'Admin\JetController@store')->name('admin.jet.store');
Route::get('/admin/jets/{jet}', 'Admin\JetController@show')->name('admin.jet.show');

Route::post('/admin/activity', 'Admin\ActivityController@store')->name('admin.activity.store');
Route::get('/admin/activity/create', 'Admin\ActivityController@singleCreate')->name('admin.1:1-activity.create');
Route::get('/admin/activity/group-create', 'Admin\ActivityController@groupCreate')->name('admin.group-activity.create');
Route::get('/admin/activity/{activity}/edit', 'Admin\ActivityController@edit')->name('admin.activity.edit');
Route::put('/admin/activity/{activity}/single', 'Admin\ActivityController@singleUpdate')->name('admin.1:1-activity.update');
Route::put('/admin/activity/{activity}/group', 'Admin\ActivityController@groupUpdate')->name('admin.group-activity.update');
Route::get('admin/jockey-resource/{coach}', 'Admin\ActivityController@getCoachesJockeys');
Route::get('/admin/activity/{activity}', 'Admin\ActivityController@show')->name('admin.activity.show');

Route::post('/admin/jockeys/{jockey}/approve', 'Admin\ApprovedJockeyController@create')->name('admin.jockey.approve');
Route::delete('/admin/jockeys/{jockey}/decline', 'Admin\ApprovedJockeyController@destroy')->name('admin.jockey.decline');
Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
// Change to PendingJockeyController@index or remove and just have in Admin dashboard.
Route::get('/admin/jockeys/pending', 'Admin\JockeyController@pending')->name('admin.jockeys.pending');


// Jets
Route::get('/jets/dashboard', 'Jet\DashboardController@index')->name('jet.dashboard.index');
Route::get('/jet/auth', 'Jet\TokenAccessController@index')->name('jet.token-access');
Route::post('/jet/auth', 'Jet\TokenAccessController@update')->name('jet.token-access.update');

// Racing Excellence
/*
Route::get('/admin/racing-excellence/create', 'Admin\RacingExcellenceController@create')->name('admin.racing-excellence.create');
Route::post('/admin/racing-excellence', 'Admin\RacingExcellenceController@store')->name('admin.racing-excellence.store');
// Route::get('/admin/racing-excellence/{racingExcellence}', 'Admin\RacingExcellenceController@show')->name('admin.racing-excellence.show');
Route::put('/admin/racing-excellence/{racingExcellence}', 'Admin\RacingExcellenceController@update')->name('admin.racing-excellence.update');

Route::get('/admin/racing-excellence/{racingExcellence}/edit', 'Admin\RacingExcellenceController@edit')->name('admin.racing-excellence.edit');

Route::get('/racing-excellence/jockey/{jockey}', 'RacingExcellence\ParticipantController@jockey');
Route::post('/racing-excellence/{racingExcellenceDivision}/participant/create', 'RacingExcellence\ParticipantController@create');
Route::post('/racing-excellence/{racingExcellenceDivision}/participant/external-create', 'RacingExcellence\ParticipantController@externalCreate');
Route::delete('/racing-excellence/participant/{racingExcellenceParticipant}', 'RacingExcellence\ParticipantController@destroy');
*/


/*
	Racing Excellence
*/
Route::group(['middleware' => ['auth']], function() {
	Route::get('/racing-excellence/{racingExcellence}', 'RacingExcellence\RacingExcellenceController@show')->name('racing-excellence.show');

});
Route::group(['middleware' => ['auth', 'role:admin']], function() {
	Route::post('/racing-excellence/{racingExcellence}/assign-coach', 'RacingExcellence\RacingExcellenceController@assignCoach');
});

Route::group(['middleware' => ['auth', 'role:admin,coach']], function() {
	Route::put('/racing-excellence/participant/{participant}', 'RacingExcellence\ParticipantResultController@update');
});





// Route::get('/racing-excellence/{racingExcellence}/results', 'RacingExcellence\RacingExcellenceResultController@create')->name('racing-excellence.results.create');



// RE - Coach
// Route::get('/coach/racing-excellence/{racingExcellence}/edit', 'Coach\RacingExcellenceController@edit')->name('admin.racing-excellence.edit');



// Competency Assessment - Coach
Route::get('/competency-assessment/create', 'CompetencyAssessment\CompetencyAssessmentController@create')->name('competency-assessment.create');
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
// admin, coach, jets only
Route::get('/messages/create', 'Message\MessageController@create')->name('message.create');
Route::post('/messages/user/{user}', 'Message\MessageController@getUser');
Route::get('/messages', 'Message\MessageController@index')->name('messages.index');
Route::get('/messages/sent', 'Message\MessageController@sentIndex')->name('messages.sent');
Route::get('/messages/{message}', 'Message\MessageController@show')->name('messages.show');
Route::post('/messages', 'Message\MessageController@store')->name('message.store');
Route::delete('/messages/{message}', 'Message\MessageController@destroy')->name('message.delete');
Route::delete('/messages/sent/{message}', 'Message\MessageController@destroySentMessage')->name('sent-message.delete');



// Attachments
Route::get('/attachment/{attachment}', 'Attachment\AttachmentController@show');
Route::post('/attachment', 'Attachment\AttachmentController@store');
Route::delete('/attachment/{attachment}', 'Attachment\AttachmentController@destroy');

// Change Password
Route::get('/profile/password', 'Auth\PasswordController@edit')->name('profile.password.edit');
Route::put('/profile/password', 'Auth\PasswordController@update')->name('profile.password.update');