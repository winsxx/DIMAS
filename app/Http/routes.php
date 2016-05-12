<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('land', ['uses' => 'DataController@testDatabase']);
Route::get('query1','DataController@userquery1');
Route::get('query2','DataController@userquery2');
Route::get('query3','DataController@userquery3');
Route::get('query4','DataController@userquery4');
Route::get('query5','DataController@userquery5');
Route::get('query6','DataController@userquery6');
Route::get('query7','DataController@userquery7');
Route::get('query8','DataController@userquery8');
Route::post('query1','DataController@query1');
Route::post('query2','DataController@query2');
Route::post('query3','DataController@query3');
Route::post('query4','DataController@query4');
Route::post('query5','DataController@query5');
Route::post('query6','DataController@query6');
Route::post('query7','DataController@query7');
Route::post('query8medical','DataController@query8medical');
Route::post('query8refugee','DataController@query8refugee');
Route::post('query8age','DataController@query8age');
Route::post('query8status','DataController@query8status');
Route::post('query8gender','DataController@query8gender');