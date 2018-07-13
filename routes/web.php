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

        Route::group(['prefix' => 'tags'], function () {
            Route::post('/new', 'API\TagController@newTag');
        });
    });

    Route::group(['prefix' => '/api/v1'], function () {
        Route::post('/login', 'API\CredentialController@login');
        Route::post('/register', 'API\CredentialController@registerUser');

        Route::group(['middleware' => VerifyAPIToken::class], function () {
            Route::delete('/logout', 'API\CredentialController@logout');

            Route::group(['prefix' => 'links'], function () {
                Route::get('/', 'API\LinkController@getLinks');
                Route::get('/search', 'API\LinkController@getLinksForSearch');
                Route::post('/new', 'API\LinkController@newLink');
                Route::get('/{link}', 'API\LinkController@getLink');
                Route::delete('/{link}', 'API\LinkController@deleteLink');
                Route::put('/{link}', 'API\LinkController@updateLink');
            });

            Route::group(['prefix' => 'tags'], function () {
                Route::get('/', 'API\TagController@getTags');
                Route::post('/new', 'API\TagController@newTag');
            });
        });
    });

    Route::post('/token', 'Auth\TokenController@newToken');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
