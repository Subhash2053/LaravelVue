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



Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/verify','Api\AuthController@verify');
   
Route::resource('roles', 'Api\RoleController');

// Users CRUD
Route::resource('users', 'UserController');
Route::post('email/verify', 'UserController@verifyEmail');
Route::post('users/delete', 'UserController@deleteAll');
Route::post('roles/delete', 'RoleController@deleteAll');
Route::post('user/role', 'UserController@changeRole');
Route::post('user/photo', 'UserController@changePhoto');

});
Route::post('/login','Api\AuthController@getUser');
