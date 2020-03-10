<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {
    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'AuthController@register');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
            Route::post('logout', 'AuthController@logout');
            Route::get('me', 'AuthController@me');
        });

        Route::group(['prefix' => 'tasks'], function () {
            Route::get('/', 'TaskController@index');
            Route::get('{id}', 'TaskController@show');
            Route::post('/', 'TaskController@store');
            Route::put('{id}', 'TaskController@update');
            Route::delete('{id}', 'TaskController@destroy');
        });
    });
});
