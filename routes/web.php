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

Route::prefix('settings')->as('settings.')->group(function(){
  Route::get('', 'HomeController@showSettings')->name('frequency');
  Route::post('', 'HomeController@updateSettings');

  Route::post('url', 'HomeController@updateUrlSettings')->name('url');

  Route::get('account', 'HomeController@showAccountSettings')->name('account');
  Route::post('account', 'HomeController@updateAccountSettings');

  Route::get('email', 'HomeController@showEmailSettings')->name('email');
  Route::post('email', 'HomeController@updateEmailSettings');

  Route::get('plan', 'HomeController@showPlans')->name('plan');
  Route::post('plan', 'HomeController@updatePlans');
});

Route::get('/', 'SupportController@index')->name('tags');
Route::post('/', 'SupportController@support');

Route::get('test', 'PostController@testForm')->name('test');
Route::post('test', 'PostController@test');
