<?php

use Illuminate\Http\Request;

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

Route::get('/', 'AppController@index');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

//Settings
Route::get('/settings', 'SettingController@index');
Route::get('/settings/organization', 'SettingController@showTab');

Route::get('/settings/profile', 'SettingController@showTab')->name('profile');
Route::get('/settings/candidate', 'SettingController@showTab');
Route::post('/organization/save', 'SettingController@saveOrganization');
// Route::post('/smtp/save', 'SettingController@saveImapSmptp');
// Route::post('/imap/save', 'SettingController@saveImapSmptp');
Route::post('/settings', 'SettingController@store')->name('settings.store'); // New setting
Route::put('/settings/{organizationsetting}', 'SettingController@update')->name("settings.update"); // Update a setting

// Profile
// Route::get('/settings/profile', 'UserController@index')->name('profile');
Route::put('/settings/profile/{user}', 'UserController@update')->name('profile.update');

Route::get('/api/disable-expired-jobs', 'SettingController@disableExpiredJobs');

//Cateogores/Jobs
Route::get('/jobs', 'CategoryController@index')->name('jobs');
Route::post('/jobs', 'CategoryController@store')->name('jobs.store');
Route::put('/jobs/{category}', 'CategoryController@update')->name('jobs.update');
Route::get('/api/jobs', 'CategoryController@getJobs');
Route::get('/jobs/create', 'CategoryController@create')->name('jobs.create');
Route::get('/jobs/{category}/edit', 'CategoryController@edit')->name('jobs.edit');
Route::delete('/jobs/delete/{id}', 'CategoryController@destroy')->name('jobs.destroy');
Route::post('/jobs/change-status/{id}', 'CategoryController@changeJobStatus')->name('jobs.change-status');


//Mailboxes
Route::get('/mailboxes', 'MailboxController@index')->name('mailboxes');
Route::post('/mailboxes', 'MailboxController@store')->name('mailboxes.store');
Route::get('/mailboxes/create', 'MailboxController@create')->name("mailboxes.create");
Route::get('/mailboxes/{mailbox}/edit', 'MailboxController@edit')->name('mailboxes.edit');
Route::put('/mailboxes/{mailbox}', 'MailboxController@update')->name("mailboxes.update");
Route::delete('/mailboxes/destroy/{mailbox}','MailboxController@destroy')->name('mailboxes.destroy');
Route::get('/api/mailboxes', 'MailboxController@getMailboxes')->name('mailboxes.getAll');
Route::put('/api/imap/test', 'MailboxController@testImap')->name('mailboxes.testImap');
Route::post('/api/imap/test', 'MailboxController@testImap')->name('mailboxes.testImap');

// Questions
Route::get('/questions', 'QuestionController@index')->name('questions');
Route::post('/questions', 'QuestionController@store')->name('questions.store');
Route::get('/questions/create', 'QuestionController@create')->name("questions.create");
Route::get('/questions/{question}/edit', 'QuestionController@edit')->name('questions.edit');
Route::put('/questions/{question}', 'QuestionController@update')->name('questions.update');
Route::delete('/questions/destroy/{question}','QuestionController@destroy')->name('questions.destroy');
Route::get('/api/questions', 'QuestionController@getQuestions');


// Applications
Route::get('/candidates', 'ApplicationController@index')->name('candidates');
Route::put('/candidates/{id}', 'ApplicationController@update')->name('candidates.update');
Route::get('/api/applications', 'ApplicationController@getApplications');
Route::get('/api/applications/{application}/{file_unique_name}', 'ApplicationController@getAttachment')->name('candidates.attachment');
Route::get('/api/send-reminder', 'ApplicationController@sendReminder')->name('candidates.sendreminder');
Route::get('/api/fetch-fresh', 'ApplicationController@fetchFresh');
Route::get('/api/fetch-questioned', 'ApplicationController@fetchQuestioned');
Route::get('/api/raise-questions', 'ApplicationController@raiseQuestions');
// Route::get('/api/send-reminders', 'ApplicationController@sendReminders');

Route::get('/api/read-replies', 'ApplicationController@readReplies');
Route::post('/api/send-reminder', 'ApplicationController@sendReminder');
Route::get('/api/generate-rating', 'ApplicationController@generateRating')->name('rating');

Route::get('/fetch-all', 'ApplicationController@fetchAll');
Route::get('/test', 'ApplicationController@test')->name('test');
Route::post('/check-cookie', 'ApplicationController@checkCookie');
Route::post('/authenticate-carbonate', 'ApplicationController@carbonateAuthentication');
Route::post('/carbonate-applicant-sync', 'ApplicationController@carbonateApplicantSync');
Route::post('/delete-cookie', 'ApplicationController@deleteCookie');

//Questionaire
Route::get('/response', 'ResponseController@index')->name('response');
Route::post('/response', 'ResponseController@store')->name('response.store');
Route::get('/response/thanks', 'ResponseController@thanks')->name('response.thanks');
Route::get('/response/expired', 'ResponseController@expired')->name('response.expired');

//Question set
Route::resource('question-sets', 'QuestionSetController');
Route::resource('tags', 'TagController');
Route::resource('functional-areas', 'FunctionalAreaController');
Route::resource('job-categories', 'JobCategoryController');
Route::get('/get-tags', 'TagController@getTags');
Route::get('/get-functional-area', 'FunctionalAreaController@getfunctionalarea');
Route::get('/get-job-categories', 'JobCategoryController@getJobCategories');
Route::get('/get-question-sets', 'QuestionSetController@getQuestionSets');


// Application Activity
Route::get('/api/application-activity/{id}', 'ApplicationController@getAppActivityByAppId');

// Route::get('/api/applications', 'ApplicationController@getApplications');

// Moderator 

Route::get('/pending-moderator', 'Moderator\PendingModeratorController@index')->name('pending-moderator.index');
Route::get('/pending-moderator/{category}/edit', 'Moderator\PendingModeratorController@edit');
Route::get('/pending-moderator/jobs', 'Moderator\PendingModeratorController@getJobs');
Route::put('/pending-moderator/jobs/{id}', 'Moderator\PendingModeratorController@update')->name('pending-moderator.update');

// Admin User Crud

Route::resource('organization/users', 'Organization\UserController');

Route::get('/get-users','Organization\UserController@getUsers');

Route::get('/threads', 'Controller@home');
Route::get('/contact', function () {
    return view('about');
})->name('contact');

Route::get('/faq', function () {
    return redirect(env('FAQ_URL'));
})->name('contact');

Route::get('/about-us', function () {
    return view('about');
});
Route::post('/save', 'SubscriberController@store');
Route::get('/privacy-policy', function () {
    return view('privacy');
});
Route::post('settings/genrate_api_token', 'SettingController@genrate_api_token');


Auth::routes();
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LoginController@logout')->name('logout');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/public_job_view/{id}', 'PublicController@showPosterJobDetail');
