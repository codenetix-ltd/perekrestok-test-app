<?php

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
Route::get('events/{event}', 'EventController@get');
Route::get('events', 'EventController@paginate');
Route::patch('events/{event}', 'EventController@updatePartially');
Route::delete('events/{event}', 'EventController@destroy');
