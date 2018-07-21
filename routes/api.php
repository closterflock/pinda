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
