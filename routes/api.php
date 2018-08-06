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

    Route::group(['middleware' => 'auth:api'], function () {
        Route::delete('/logout', 'API\CredentialController@logout')->name('logout');

        Route::prefix('links')->name('links.')->group(function () {
            Route::get('/', 'API\LinkController@getLinks')->name('getLinks');
            Route::post('/', 'API\LinkController@newLink')->name('store');
            Route::get('/{link}', 'API\LinkController@getLink')
                ->middleware('can:view,link')
                ->name('getLink');
            Route::delete('/{link}', 'API\LinkController@deleteLink')
                ->middleware('can:delete,link')
                ->name('delete');
            Route::put('/{link}', 'API\LinkController@updateLink')
                ->middleware('can:update,link')
                ->name('update');
        });

        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', 'API\TagController@getTags')->name('getTags');
            Route::post('/', 'API\TagController@newTag')->name('store');
        });

        Route::get('sync', 'API\SyncController@syncData')->name('sync');
    });
});
