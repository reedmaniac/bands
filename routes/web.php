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

Route::pattern('id', '[0-9]+');

// ------------------------------------------------------------
//  Bands
// ------------------------------------------------------------

Route::get('/', function () {
    return view('layout', ['component' => 'bands']);
})->name('bands');

Route::get('bands', function () {
    return view('layout', ['component' => 'bands']);
});

Route::get('bands/{id}', function ($id) {
    return view('layout', ['component' => 'view-band v-bind:id="'.$id.'"']);
});

Route::get('bands/edit/{id}', function ($id) {
    return view('layout', ['component' => 'edit-band v-bind:id="'.$id.'"']);
});

Route::get('bands/new', function () {
    return view('layout', ['component' => 'edit-band']);
});

// ------------------------------------------------------------
// Albums
// ------------------------------------------------------------

Route::get('/albums', function () {
    return view('layout', ['component' => 'albums']);
})->name('albums');

Route::get('albums/{id}', function ($id) {
    return view('layout', ['component' => 'view-album v-bind:id="'.$id.'"']);
});

Route::get('albums/edit/{id}', function ($id) {
    return view('layout', ['component' => 'edit-album v-bind:id="'.$id.'"']);
});

Route::get('albums/new', function () {
    return view('layout', ['component' => 'edit-album']);
});