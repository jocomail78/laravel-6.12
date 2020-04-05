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
    return view('index');
});

Auth::routes(['verify' => true]);

Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('authenticatedVerifiedAcceptedTerms');

Route::get('/terms/latest','TermsController@latest');
Route::get('/terms/{id}/publish','TermsController@publish');
Route::resource('terms','TermsController');

Route::get('/users/accept-latest-terms','UsersController@acceptTerms');
Route::get('/users/unverify/{id}','UsersController@unverify');
Route::get('/users/search/{term}','UsersController@search');
Route::get('/users/search','UsersController@search');

Route::resource('users','UsersController')->middleware('authenticatedVerifiedAcceptedTerms');
