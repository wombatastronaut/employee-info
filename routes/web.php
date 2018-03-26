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
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('htmltopdfview', array('as'=>'htmltopdfview','uses'=>'HomeController@htmltopdfview'));

Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('/employees/get-all-for-table', ['as' => 'employees.get-all-for-table', 'uses' => 'Api\EmployeesController@getAllForTable']);
    Route::post('/employees/post', ['as' => 'employees.post', 'uses' => 'Api\EmployeesController@post']);
    Route::get('/employees/delete/{id}', ['as' => 'employees.delete', 'uses' => 'Api\EmployeesController@delete']);
});