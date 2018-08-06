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
    Route::post('/login', 'CredentialController@login')->name('login');
    Route::post('/register', 'CredentialController@registerUser')->name('register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::delete('/logout', 'CredentialController@logout')->name('logout');

        Route::prefix('links')->name('links.')->group(function () {
            Route::get('/', 'LinkController@getLinks')->name('getLinks');
            Route::post('/', 'LinkController@newLink')->name('store');
            Route::get('/{link}', 'LinkController@getLink')
                ->middleware('can:view,link')
                ->name('getLink');
            Route::delete('/{link}', 'LinkController@deleteLink')
                ->middleware('can:delete,link')
                ->name('delete');
            Route::put('/{link}', 'LinkController@updateLink')
                ->middleware('can:update,link')
                ->name('update');
        });

        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', 'TagController@getTags')->name('getTags');
            Route::post('/', 'TagController@newTag')->name('store');
            Route::prefix('{tag}')->group(function () {
                Route::get('/', 'TagController@getTag')
                    ->middleware('can:view,tag')
                    ->name('getTag');
                Route::put('/', 'TagController@updateTag')
                    ->middleware('can:update,tag')
                    ->name('update');
                Route::delete('/', 'TagController@deleteTag')
                    ->middleware('can:delete,tag')
                    ->name('delete');
            });
        });

        Route::get('sync', 'SyncController@syncData')->name('sync');
    });
});
