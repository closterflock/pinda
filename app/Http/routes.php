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

use App\Http\Middleware\VerifyAPIToken;

Route::auth();

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');

    Route::group(['prefix' => 'link'], function () {
        Route::get('/', 'LinkController@index');
        Route::post('/new', 'LinkController@newLink');
        Route::group(['prefix' => '{link}'], function () {
            Route::get('/', 'LinkController@editIndex');
            Route::post('/save', 'LinkController@updateLink');
            Route::get('/delete', 'LinkController@deleteLink');
        });
    });

    Route::group(['prefix' => '/api/v1', 'middleware' => VerifyAPIToken::class], function () {
        Route::group(['prefix' => 'links'], function () {
            Route::get('/', 'API\LinkController@getLinks');
            Route::get('/search', 'API\LinkController@getLinksForSearch');
            Route::put('/new', 'API\LinkController@newLink');
            Route::get('/{link}', 'API\LinkController@getLink');
            Route::delete('/{link}', 'API\LinkController@deleteLink');
            Route::put('/{link}', 'API\LinkController@updateLink');
        });
    });

    Route::post('/token', 'Auth\TokenController@newToken');
});