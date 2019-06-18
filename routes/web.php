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

Route::get('/planning', 'PlanningController@index')->name('planning');

Route::get('/planning/add', 'PlanningController@create')->name('planning');

Route::any('/planning/activity/{id}/edit', 'PlanningController@edit')->name('activity');

Route::get('/workers', 'WorkerController@index')->name('worker');

//Route::resource('planning','PlanningController');