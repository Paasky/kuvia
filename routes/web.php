<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'collages', 'middleware' => 'auth'], function() {
    Route::get('create', function() {
        return \Illuminate\Support\Facades\View::make('collages-create');
    });
    Route::post('', 'CollageController@create');

    Route::get('', function() {
        return \Illuminate\Support\Facades\View::make(
            'collages',
            [ 'collages' => Auth::user()->collages ]
        );
    });

    Route::get('{id}', 'CollageController@show');
    Route::get('{collage}/images', 'CollageController@images');
    Route::get('{collage}/ui-images', 'CollageController@imagesForUi');
    Route::get('{collage}/ui-images/after-image-id/{afterImageId}', 'CollageController@imagesForUi');
    Route::get('{collage}/delete', 'CollageController@delete');
});

Route::group(['prefix' => 'u'], function() {
    Route::get('{collageKey}', 'CollageController@showUploadImage');
    Route::post('{collageKey}', 'CollageController@uploadImage')
        ->middleware(\Spatie\Honeypot\ProtectAgainstSpam::class);
});

Route::group(['prefix' => 'images'], function() {
    Route::get('{image}', 'ImageController@get');
    Route::get('{image}/delete', 'ImageController@delete');
});
