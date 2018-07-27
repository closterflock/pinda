<?php

use App\Http\Middleware\VerifyAPIToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/login', 'API\CredentialController@login')->name('login');
    Route::post('/register', 'API\CredentialController@registerUser')->name('register');

    Route::group(['middleware' => VerifyAPIToken::class], function () {
        Route::delete('/logout', 'API\CredentialController@logout')->name('logout');

        Route::prefix('links')->name('links.')->group(function () {
            Route::get('/', 'API\LinkController@getLinks')->name('getLinks');
            Route::post('/new', 'API\LinkController@newLink')->name('new');
            Route::get('/{link}', 'API\LinkController@getLink')->name('getLink');
            Route::delete('/{link}', 'API\LinkController@deleteLink')->name('delete');
            Route::put('/{link}', 'API\LinkController@updateLink')->name('update');
        });

        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', 'API\TagController@getTags')->name('getTags');
            Route::post('/new', 'API\TagController@newTag')->name('create');
        });

        Route::get('sync', 'API\SyncController@syncData')->name('sync');
    });
});
