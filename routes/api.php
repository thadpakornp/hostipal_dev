<?php
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
Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::get('prefix', ['as' => 'prefix', 'uses' => 'Api\RegisterController@prefix']);
    Route::post('register', ['as' => 'register', 'uses' => 'Api\RegisterController@stored']);
    Route::post('forget', ['as' => 'forget', 'uses' => 'Api\ForgotController']);
    Route::post('login', ['as' => 'login', 'uses' => 'Api\LoginController@login']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Api\LoginController@logout']);

    Route::group(['middleware' => ['user.api', 'auth:api', 'activity.log']], function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/profile', ['as' => 'profile', 'uses' => 'Api\UserController@getProfile']);
            Route::get('/get', ['as' => 'get', 'uses' => 'Api\UserController@getUser']);
            Route::put('/updated', ['as' => 'updated', 'uses' => 'Api\UserController@updated']);
        });

        Route::group(['prefix' => 'charts', 'as' => 'charts.'], function () {
            Route::get('/index', ['as' => 'index', 'uses' => 'Api\ChartsController@users']);
            Route::post('/uploaded', ['as' => 'uploaded', 'uses' => 'Api\ChartsController@uploaded']);
            Route::post('/stored', ['as' => 'stored', 'uses' => 'Api\ChartsController@stored']);
            Route::post('/descriptions', ['as' => 'descriptions', 'uses' => 'Api\ChartsController@descriptions']);
            Route::post('/files', ['as' => 'files', 'uses' => 'Api\ChartsController@files']);
        });

        Route::group(['prefix' => 'manage', 'as' => 'manage.'], function () {
            Route::get('/index', ['as' => 'index', 'uses' => 'Api\ManageController@index']);
            Route::get('/index/{id}', ['as' => 'index', 'uses' => 'Api\ManageController@office']);
            Route::post('/office/stored', ['as' => 'office.stored', 'uses' => 'Api\ManageController@stored']);
            Route::get('/office/{id}/destroy', ['as' => 'office.destroy', 'uses' => 'Api\ManageController@destroy']);
            Route::get('/getAddress', ['as' => 'address', 'uses' => 'Api\ManageController@getAddress']);
            Route::get('/office/{id}/edit', ['as' => 'office.edit', 'uses' => 'Api\ManageController@edit']);
        });
    });
});


