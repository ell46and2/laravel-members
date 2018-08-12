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
	Route::get('/invoices/invoice/{invoice}/pdf', 'Invoice\InvoiceController@pdf')->name('invoice.pdf');
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

	Route::get('/invoices/invoice/{invoice}/line/{invoiceLine}', 'Invoice\InvoiceController@editLine')->name('invoice-line.edit');
	Route::put('/invoices/invoice/{invoice}/line/{invoiceLine}', 'Invoice\InvoiceController@updateLine')->name('invoice-line.update');
});



// Pdp
Route::group(['middleware' => ['auth','role:jockey,jets,admin']], function() {
	Route::get('/pdp/{jockey}/list', 'Pdp\PdpController@index')->name('pdp.list');
	Route::post('/pdp/{jockey}/create', 'Pdp\PdpController@store')->name('pdp.create');
	Route::get('pdp/{pdp}/personal-details', 'Pdp\PdpController@personalDetails')->name('pdp.personal-details');
	Route::post('pdp/{pdp}/personal-details', 'Pdp\PdpController@personalDetailsStore')->name('pdp.personal-details.store');
	Route::get('pdp/{pdp}/career', 'Pdp\PdpController@career')->name('pdp.career');
	Route::post('pdp/{pdp}/career', 'Pdp\PdpController@careerStore')->name('pdp.career.store');
	Route::get('pdp/{pdp}/nutrition', 'Pdp\PdpController@nutrition')->name('pdp.nutrition');
	Route::post('pdp/{pdp}/nutrition', 'Pdp\PdpController@nutritionStore')->name('pdp.nutrition.store');
	Route::get('pdp/{pdp}/physical', 'Pdp\PdpController@physical')->name('pdp.physical');
	Route::post('pdp/{pdp}/physical', 'Pdp\PdpController@physicalStore')->name('pdp.physical.store');
	Route::get('pdp/{pdp}/communication-media', 'Pdp\PdpController@communicationMedia')->name('pdp.communication-media');
	Route::post('pdp/{pdp}/communication-media', 'Pdp\PdpController@communicationMediaStore')->name('pdp.communication-media.store');
	Route::get('pdp/{pdp}/personal-well-being', 'Pdp\PdpController@personalWellBeing')->name('pdp.personal-well-being');
	Route::post('pdp/{pdp}/personal-well-being', 'Pdp\PdpController@personalWellBeingStore')->name('pdp.personal-well-being.store');
	Route::get('pdp/{pdp}/managing-finance', 'Pdp\PdpController@managingFinance')->name('pdp.managing-finance');
	Route::post('pdp/{pdp}/managing-finance', 'Pdp\PdpController@managingFinanceStore')->name('pdp.managing-finance.store');
	Route::get('pdp/{pdp}/sports-psychology', 'Pdp\PdpController@sportsPsychology')->name('pdp.sports-psychology');
	Route::post('pdp/{pdp}/sports-psychology', 'Pdp\PdpController@sportsPsychologyStore')->name('pdp.sports-psychology.store');
	Route::get('pdp/{pdp}/mental-well-being', 'Pdp\PdpController@mentalWellBeing')->name('pdp.mental-well-being');
	Route::post('pdp/{pdp}/mental-well-being', 'Pdp\PdpController@mentalWellBeingStore')->name('pdp.mental-well-being.store');
	Route::get('pdp/{pdp}/interests-hobbies', 'Pdp\PdpController@interestsHobbies')->name('pdp.interests-hobbies');
	Route::post('pdp/{pdp}/interests-hobbies', 'Pdp\PdpController@interestsHobbiesStore')->name('pdp.interests-hobbies.store');
	Route::get('pdp/{pdp}/performance-goals', 'Pdp\PdpController@performanceGoals')->name('pdp.performance-goals');
	Route::post('pdp/{pdp}/performance-goals', 'Pdp\PdpController@performanceGoalsStore')->name('pdp.performance-goals.store');
	Route::get('pdp/{pdp}/actions', 'Pdp\PdpController@actions')->name('pdp.actions');
	Route::post('pdp/{pdp}/actions', 'Pdp\PdpController@actionsStore')->name('pdp.actions.store');
	Route::get('pdp/{pdp}/support-team', 'Pdp\PdpController@supportTeam')->name('pdp.support-team');
	Route::post('pdp/{pdp}/support-team', 'Pdp\PdpController@supportTeamStore')->name('pdp.support-team.store');
	Route::get('pdp/{pdp}/submit', 'Pdp\PdpController@submit')->name('pdp.submit');
	Route::post('pdp/{pdp}/submit', 'Pdp\PdpController@submitStore')->name('pdp.submit.store');
	Route::post('pdp/{pdp}/complete', 'Pdp\PdpController@complete')->name('pdp.complete');
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

Route::get('/jets/dashboard', 'Jet\DashboardController@index')->name('jet.dashboard.index');
Route::get('/jets/{jet}', 'Jet\JetController@show')->name('jet.show');
Route::put('/jets/{jet}/edit', 'Jet\JetController@updateProfile')->name('jet.update');
Route::put('/jets/{jet}/status', 'Jet\JetController@updateStatus')->name('jet.status.update');


// Jockey
Route::get('/profile', 'Jockey\ProfileController@index')->name('jockey.profile.index');
Route::get('/profile/edit', 'Jockey\ProfileController@edit')->name('jockey.profile.edit');
Route::put('/profile/edit', 'Jockey\ProfileController@update')->name('jockey.profile.update');

Route::get('/activity/log', 'Jockey\ActivityController@index')->name('jockey.activity-log');

Route::get('/activity/{activity}', 'Activity\ActivityController@show')->name('activity.show');

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
// Route::get('/coach/activity/{activity}', 'Coach\ActivityController@show')->name('coach.activity.show');


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
// Route::get('/admin/activity/{activity}', 'Admin\ActivityController@show')->name('admin.activity.show');

Route::post('/admin/jockeys/{jockey}/approve', 'Admin\ApprovedJockeyController@create')->name('admin.jockey.approve');
Route::delete('/admin/jockeys/{jockey}/decline', 'Admin\ApprovedJockeyController@destroy')->name('admin.jockey.decline');
Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
// Change to PendingJockeyController@index or remove and just have in Admin dashboard.
Route::get('/admin/jockeys/pending', 'Admin\JockeyController@pending')->name('admin.jockeys.pending');


// Jets

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



// Skills Profile - Coach
Route::group(['middleware' => ['auth', 'role:admin,coach']], function() {
	Route::get('/skills-profile/create', 'SkillProfile\SkillProfileController@create')->name('skill-profile.create');
	Route::post('/coach/skills-profile', 'SkillProfile\SkillProfileController@store')->name('skill-profile.store');
});

Route::group(['middleware' => ['auth', 'role:admin,coach,jockey']], function() {
	Route::get('/skills-profile/{skillProfile}', 'SkillProfile\SkillProfileController@show')->name('skill-profile.show');
	Route::get('/skills-profile/{skillProfile}/edit', 'SkillProfile\SkillProfileController@edit')->name('skill-profile.edit');
	Route::put('/skills-profile/{skillProfile}/update', 'SkillProfile\SkillProfileController@update')->name('skill-profile.update');
});



// Documents
Route::group(['middleware' => ['auth', 'role:admin,coach']], function() {
	Route::get('/admin/documents/create', 'Admin\DocumentController@create')->name('admin.document.create');
	Route::post('/admin/documents', 'Admin\DocumentController@store')->name('admin.document.store');
	Route::put('/admin/documents/{document}', 'Admin\DocumentController@update')->name('admin.document.update');
	Route::delete('/admin/documents/{document}', 'Admin\DocumentController@destroy')->name('admin.document.delete');
});

Route::group(['middleware' => ['auth']], function() {
	Route::get('/documents', 'Document\DocumentController@index')->name('documents.index');
});



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

// avatar
Route::post('/avatar/{user}', 'Avatar\AvatarController@store');
Route::delete('/avatar/{user}', 'Avatar\AvatarController@destroy');

// Change Password
// Route::get('/profile/password', 'Auth\PasswordController@edit')->name('profile.password.edit');
Route::put('/profile/password', 'Auth\PasswordController@update')->name('profile.password.update');