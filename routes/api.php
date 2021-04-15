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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('verify_api_key')->post('jobs/update-status','Api\JobController@changeJobStatus');
Route::middleware('verify_api_key')->put('jobs/update-job/{id}','Api\JobController@update');
// Route::middleware('verify_api_key')->post('applicant/create','Api\JobController@addApplicant');
Route::middleware('verify_api_key')->get('jobs/get-all-jobs','Api\JobController@getActiveJobs');
Route::middleware('verify_api_key')->get('jobs/locations','Api\JobController@getLocations');
Route::middleware('verify_api_key')->post('poster','Api\JobController@getJobPosterList');
Route::middleware('verify_api_key')->resource('jobs', 'Api\JobController');
Route::middleware('verify_api_key')->resource('tags', 'Api\TagController');
Route::middleware('verify_api_key')->resource('departments', 'Api\DepartmentController');
Route::middleware('verify_api_key')->resource('job-categories', 'Api\JobCategoryController');
Route::middleware('verify_api_key')->resource('question-sets', 'Api\QuestionSetController');
Route::middleware('verify_api_key')->resource('applicant', 'Api\ApplicantController');
Route::middleware('verify_api_key')->get('currencies', 'Api\JobController@getCurrencyCode');
Route::middleware('verify_api_key')->get('countries', 'Api\JobController@getCountries');
Route::middleware('verify_api_key')->get('countries/{code}/cities', 'Api\JobController@getCitiesByCountry');
Route::middleware('verify_api_key')->get('migrate-user-detail', 'Api\MigrationController@changeUserDetail');

