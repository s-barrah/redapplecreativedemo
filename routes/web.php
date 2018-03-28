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

Route::get('/tracks', 'TrackController@index')->name('tracks');

Route::prefix('track')->group(function () {

    Route::post('/add', 'TrackController@create')->name('track.add');
    Route::post('/details', 'TrackController@get_track')->name('track.details');
    Route::post('/update', 'TrackController@update')->name('track.update');
    Route::post('/playlist/add', 'TrackController@add_to_playlist')->name('track.playlist.add');
    Route::post('/playlist/remove', 'TrackController@remove_from_playlist')->name('track.playlist.remove');

    Route::post('/delete', 'TrackController@delete_tracks')->name('track.delete');
    //Route::get('/', 'TrackController@index')->name('tracks');

});

Route::get('/playlists', 'PlaylistController@index')->name('playlists');

Route::prefix('playlist')->group(function () {

    Route::post('/add', 'PlaylistController@create')->name('playlist.add');
    Route::post('/details', 'PlaylistController@get_playlist')->name('playlist.details');
    Route::post('/update', 'PlaylistController@update')->name('playlist.update');
    Route::get('/view/{id}/{random}', 'PlaylistController@view')->name('playlist.view');
    Route::post('/delete', 'PlaylistController@delete_playlists')->name('playlist.delete');

});



