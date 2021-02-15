<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/






Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    //guest  user
    Route::group(['namespace' => 'Site', 'middleware' => 'guest'], function () {
        route::get('/','HomeController@home') -> name('home') -> middleware('verifiedUser');
       // route::get('category/{slug}','CategoryController@productsBySlug') ->name('category');
    });


    // must be authenticated user and verified by code
    Route::group(['namespace' => 'Site', 'middleware' => ['auth','verifiedUser']], function () {  //[64]

        Route::get('profile',function(){
            return ('you are auth');
        });

    });

    // must be authenticated user but not verified by code
    Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
        Route::get('verify', 'VerificationCodeController@getVerifyPage') -> name('get.verification.form');  // Confirm code number
        Route::post('verify-user/', 'VerificationCodeController@verify') -> name('verify-user');  // [64] action Confirm code number

    });




});



