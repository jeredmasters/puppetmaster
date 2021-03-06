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

Route::get('/', 'HomeController@index');
Route::post('analyses/', 'HomeController@analyses');
Route::post('results/', 'HomeController@results');
Route::any('bellcurve/', 'HomeController@bellcurve');

Route::get('dump/{type}', 'ExportController@dump');
