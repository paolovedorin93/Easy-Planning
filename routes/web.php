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

Route::get('/risorse', function() {
    return view('soon');
});

Route::get('/planning', 'PlanningController@index')->name('planning');
//Route::any('/planning/add', 'PlanningController@create')->name('planningAdd');
Route::get('planning/{id}/edit', 'PlanningController@edit')->name('activity');
Route::get('planning/{id}/{notification}/edit', 'PlanningController@editNotification')->name('activity');
//Route::get('planning/{id}/delete', 'PlanningController@delete');
Route::any('/planning/{id}/edited', ['as'=>'new_activity','uses'=>'PlanningController@update']);
Route::get('/planning/add', ['as'=>'new_activity','uses'=>'PlanningController@create']);
Route::any('/planning/added', ['as'=>'new_activity','uses'=>'PlanningController@store']);
Route::any('/planning/addedActivity', ['as'=>'new_activity','uses'=>'PlanningController@storeActivity']);
Route::any('/planning/{id}/deletedActivity', ['as'=>'del_activity','uses'=>'PlanningController@destroyActivity']);
Route::any('/planning/{id}/deleted', ['as'=>'del_activity','uses'=>'PlanningController@destroy']);
Route::any('/planning/addWeekly', ['as'=>'indexWeekly','uses'=>'PlanningController@indexWeekly']);
Route::any('/planning/storedWeekly', ['as'=>'storeWeekly','uses'=>'PlanningController@storeWeeklyActivity']);
Route::any('/planning/storedIntensity', ['as'=>'storeIntense','uses'=>'PlanningController@updateIntensity']);
Route::get('/planning/ferie', 'PlanningController@indexVacation');
Route::any('/planning/storedComment', ['as'=>'new_comment','uses'=>'PlanningController@storeComment']);
Route::any('/planning/storedVacation',['as'=>'new_comment','uses'=>'PlanningController@storeVacation']);
Route::any('/planning/{id}/editedComment', ['as'=>'new_activity','uses'=>'PlanningController@updateComment']);
Route::any('/planning/{id}/deletedComment', ['as'=>'del_comment','uses'=>'PlanningController@destroyComment']);
Route::get('/planning/{user}/allActivity', ['as'=>'all_activity','uses'=>'PlanningController@showAllActivity']);
Route::any('/planning/filtered', ['as'=>'filtered_activity','uses'=>'PlanningController@filterVacation']);
Route::any('/planning/confirmedVacation', ['as'=>'filtered_activity','uses'=>'PlanningController@confirmVacation']);

//Route::resource('planning','PlanningController'); --> NON FUNZIONA IL RESTO DELLE FUNZIONI

Route::get('/workers', 'WorkerController@index')->name('worker');
Route::any('/workers/added', ['as'=>'updating','uses'=>'WorkerController@update']);
