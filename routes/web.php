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

Auth::routes(['verify' => true]);

Route::get('logout', 'HomeController@logout')->name('logout');
Route::get('discard', 'HomeController@discard')->name('discard');


Route::get('settings', 'HomeController@showSettings')->name('settings');
Route::post('settings', 'HomeController@updateSettings');

Route::get('/', 'SupportController@index')->name('tags');
Route::post('/', 'SupportController@support');

Route::get('test', 'PostController@testForm')->name('test');
Route::post('test', 'PostController@test');
