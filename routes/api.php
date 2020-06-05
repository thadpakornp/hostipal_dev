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
    Route::get('checkVersion', function() {
        return response()->json([
            'code' => '200',
            'data' => '3.0.0'
        ]); 
    });
    Route::get('prefix', ['as' => 'prefix', 'uses' => 'Api\RegisterController@prefix']);
    Route::post('register', ['as' => 'register', 'uses' => 'Api\RegisterController@stored']);
    Route::post('forget', ['as' => 'forget', 'uses' => 'Api\ForgotController']);
    Route::post('login', ['as' => 'login', 'uses' => 'Api\LoginController@login']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Api\LoginController@logout']);
    

    Route::group(['middleware' => ['user.api', 'auth:api', 'activity.log']], function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::post('/password', ['as' => 'password', 'uses' => 'Api\UserController@password']);
            Route::get('/profile', ['as' => 'profile', 'uses' => 'Api\UserController@getProfile']);
            Route::get('/get', ['as' => 'get', 'uses' => 'Api\UserController@getUser']);
            Route::post('/updated', ['as' => 'updated', 'uses' => 'Api\UserController@updated']);
            Route::post('token', ['as' => 'token', 'uses' => 'Api\UserController@token']);
            Route::post('/id',['as' => 'id', 'uses' => 'Api\UserController@getID']);
        });

        Route::group(['prefix' => 'charts', 'as' => 'charts.'], function () {
            Route::get('/index/{status}', ['as' => 'index', 'uses' => 'Api\ChartsController@users']);
            Route::post('/uploaded', ['as' => 'uploaded', 'uses' => 'Api\ChartsController@uploaded']);
            Route::post('/stored', ['as' => 'stored', 'uses' => 'Api\ChartsController@stored']);
            Route::post('/descriptions', ['as' => 'descriptions', 'uses' => 'Api\ChartsController@descriptions']);
            Route::post('/descriptions/success', ['as' => 'descriptions.success', 'uses' => 'Api\ChartsController@success']);
            Route::post('/descriptions/deleted', ['as' => 'descriptions.deleted', 'uses' => 'Api\ChartsController@deleted']);
            Route::post('/files', ['as' => 'files', 'uses' => 'Api\ChartsController@files']);
            Route::post('/stw', ['as' => 'stw', 'uses' => 'Api\ChartsController@sentNotifyWeb']);
            Route::post('/stwsb', ['as' => 'stwsb', 'uses' => 'Api\ChartsController@sentNotifyWebAndMobile']);
            Route::get('/chats', ['as' => 'chats', 'uses' => 'Api\ChartsController@chats']);
            Route::post('/chat/uploaded', ['as' => 'chatUpload', 'uses' => 'Api\ChartsController@chatUpload']);
            Route::post('/chat/lastProcess', ['as' => 'lastProcess', 'uses' => 'Api\ChartsController@lastProcessChat']);
            Route::post('/chat/images', ['as' => 'chatimages', 'uses' => 'Api\ChartsController@chatimages']);
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
