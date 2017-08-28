<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('bands', 'Api\BandsController@index');
Route::post('bands', 'Api\BandsController@store');
Route::put('bands/{id}', 'Api\BandsController@update');
Route::delete('bands/{id}', 'Api\BandsController@delete');
Route::get('bands/{id}', 'Api\BandsController@show');


Route::get('albums', 'Api\AlbumsController@index');
Route::post('albums', 'Api\AlbumsController@store');
Route::put('albums/{id}', 'Api\AlbumsController@update');
Route::delete('albums/{id}', 'Api\AlbumsController@delete');
Route::get('albums/{id}', 'Api\AlbumsController@show');