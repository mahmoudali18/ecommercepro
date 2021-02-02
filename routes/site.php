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



route::get('/',function(){
    return view('front.home');
}) -> name('home');


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    // must be authenticated user and verified by code
    Route::group(['namespace' => 'Site', 'middleware' => ['auth','verifiedUser']], function () {  //[64]

        Route::get('profile',function(){
            return ('you are auth');
        });

    });

    // must be authenticated user but not verified by code
    Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
        Route::post('verify-user/', 'VerificationCodeController@verify') -> name('verify-user');  //action Confirm code number

    });

    //guest  user
    Route::group(['namespace' => 'Site', 'middleware' => 'guest'], function () {


    });

    Route::get('verify',function(){  //[64]
        return view('auth.verification');
    });


});



