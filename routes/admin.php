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


// route for mcamera translation
Route::group(           //[17]
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {

    //route for  auth admin
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'auth:admin','prefix'=>'admin'], function () {       // [7]

        Route::get('/', 'DashboardController@index')->name('admin.dashboard');  // [11]

        Route::group(['prefix' => 'settings'], function () {      // [16]
            Route::get('shipping-methods/{type}', 'SettingsController@editShippingMethods')->name('edit.shippings.methods');      // [16]
            Route::put('shipping-methods/{id}', 'SettingsController@updateShippingMethods')->name('update.shippings.methods');      // [16]
        });

    });


    //route for non auth  "guest"
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin','prefix'=>'admin'], function () {
        Route::get('login', 'LoginController@login')->name('admin.login');   // [9]
        Route::post('login', 'LoginController@postLogin')->name('admin.post.login');   // [10]
    });


});
