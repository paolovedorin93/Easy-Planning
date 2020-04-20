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
Route::get('/planning/add', 'PlanningController@create')->name('planning');
Route::get('planning/{id}/edit', 'PlanningController@edit')->name('activity');
//Route::get('planning/{id}/delete', 'PlanningController@delete');
Route::any('/planning/{id}/edited',['as'=>'new_activity','uses'=>'PlanningController@update']);
Route::get('/planning/add',['as'=>'new_activity','uses'=>'PlanningController@create']);
Route::any('/planning/added',['as'=>'new_activity','uses'=>'PlanningController@store']);
Route::any('/planning/addedActivity',['as'=>'new_activity','uses'=>'PlanningController@storeActivity']);
Route::any('/planning/{id}/deleted', ['as'=>'del_activity','uses'=>'PlanningController@destroy']);
Route::any('/planning/addWeekly', ['as'=>'indexWeekly','uses'=>'PlanningController@indexWeekly']);
Route::any('/planning/storedWeekly', ['as'=>'storeWeekly','uses'=>'PlanningController@storeWeeklyActivity']);
Route::any('/planning/storedIntensity', ['as'=>'storeIntense','uses'=>'PlanningController@updateIntensity']);
Route::get('/planning/ferie', 'PlanningController@indexVacation');

//Route::resource('planning','PlanningController'); --> NON FUNZIONA IL RESTO DELLE FUNZIONI

Route::get('/workers', 'WorkerController@index')->name('worker');
Route::any('/workers/added', ['as'=>'updating','uses'=>'WorkerController@update']);
