<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// the  prefix  is admin for all file route


Route::group(['namespace'=>'Dashboard','middleware'=>'auth:admin'],function(){       // [7]
    Route::get('/','DashboardController@index')->name('admin.dashboard');  // [11]
});



//route for non auth
Route::group(['namespace'=>'Dashboard','middleware'=>'guest:admin'],function(){
    Route::get('login','LoginController@login')->name('admin.login');   // [9]
    Route::post('login','LoginController@postLogin')->name('admin.post.login');   // [10]
});
