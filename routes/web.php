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
Route::get('policy', function() {
    return view('policy');
});
Route::get('terms', function() {
    return view('terms');
});
Route::get('/', function () {
    return view('landing.index');
});
Route::get('/register2', 'Auth\RegisterController2@index');
Route::post('/register2/posted', 'Auth\RegisterController2@posted');

Auth::routes();
Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'activity.log']], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'MainController@index']);

    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::post('/', ['as' => 'index', 'uses' => 'ChatsController@index']);
    });

    //Profile
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ProfileController@index']);
        Route::get('/create', ['as' => 'create', 'uses' => 'ProfileController@create']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProfileController@edit']);
        Route::post('/created', ['as' => 'created', 'uses' => 'ProfileController@created']);
        Route::post('/updated', ['as' => 'updated', 'uses' => 'ProfileController@updated']);
        Route::post('/edited', ['as' => 'edited', 'uses' => 'ProfileController@edited']);
        Route::post('/destroy', ['as' => 'destroy', 'uses' => 'ProfileController@destroy']);
        Route::post('/search', ['as' => 'search', 'uses' => 'SearchController@searched']);
        Route::post('/details', ['as' => 'details', 'uses' => 'ProfileController@details']);
        Route::post('/approved', ['as' => 'approved', 'uses' => 'ProfileController@approved']);
        Route::post('/rejected', ['as' => 'rejected', 'uses' => 'ProfileController@rejected']);
        Route::post('/logoutonweb', ['as'=>'logoutonweb','uses'=>'ProfileController@logoutonweb']);
    });

    //Charts
    Route::group(['prefix' => 'charts', 'as' => 'charts.', 'middleware' => ['user.status']], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ChartsController@index']);
        Route::get('/users', ['as' => 'users', 'uses' => 'ChartsController@users']);
        Route::get('/{idcard}/feeds', ['as' => 'feeds', 'uses' => 'ChartsController@feeds']);
        Route::get('/{id}/feed', ['as' => 'feed', 'uses' => 'ChartsController@feed']);
        Route::get('/{id}/files', ['as' => 'files', 'uses' => 'ChartsController@files']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ChartsController@edit']);
        Route::post('/edited', ['as' => 'edited', 'uses' => 'ChartsController@edited']);
        Route::post('/stored', ['as' => 'stored', 'uses' => 'ChartsController@stored']);
        Route::post('/destroy/file', ['as' => 'destroy.file', 'uses' => 'ChartsController@fileDestroy']);
        Route::post('/description/stored', ['as' => 'description.stored', 'uses' => 'ChartsController@descrtipionStored']);
        Route::get('/{id}/files/download', ['as' => 'description.file.download', 'uses' => 'ChartsController@download']);
        Route::post('/description/destroy', ['as' => 'description.destroy', 'uses' => 'ChartsController@destroy']);
        Route::post('/chats/destroy', ['as' => 'chats.destroy', 'uses' => 'ChartsController@destroyChats']);
        Route::post('/files/destroy', ['as' => 'files.destroy', 'uses' => 'ChartsController@filesDestroy']);
        Route::post('/files/deleted', ['as' => 'files.deleted', 'uses' => 'ChartsController@filesDeleted']);
        Route::post('/description/edit', ['as' => 'description.edit', 'uses' => 'ChartsController@descrtipionEdit']);
        Route::post('/description/edited', ['as' => 'description.edited', 'uses' => 'ChartsController@descrtipionEdited']);
        Route::post('/search', ['as' => 'search', 'uses' => 'SearchController@searchedChart']);
        Route::post('/uploaded', ['as' => 'uploaded', 'uses' => 'ChartsController@uploaded']);
        Route::get('/get/data/app', ['as' => 'data.app', 'uses' => 'ChartsController@datafromApp']);
        Route::get('/get/{id}/files/show', ['as' => 'file.show', 'uses' => 'ChartsController@showFiles']);
        Route::post('/i_check', ['as' => 'i.check', 'uses' => 'ChartsController@iCheck']);
        Route::post('/success/chart', ['as' => 'success.chart', 'uses' => 'ChartsController@successChart']);
    });

    //Manage
    Route::group(['prefix' => 'manage', 'as' => 'manage.', 'middleware' => ['user.status']], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ManageController@index']);
        Route::get('/create', ['as' => 'create', 'uses' => 'ManageController@create']);
        Route::post('/stored', ['as' => 'stored', 'uses' => 'ManageController@stored']);
        Route::post('/getAddress', ['as' => 'getAddress', 'uses' => 'ManageController@getAddress']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ManageController@edit']);
        Route::post('/destroy', ['as' => 'destroy', 'uses' => 'ManageController@destroy']);
    });
});
