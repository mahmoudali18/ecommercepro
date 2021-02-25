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
    Route::group(['namespace' => 'Site'/*, 'middleware' => 'guest'*/], function () {
        route::get('/','HomeController@home') -> name('home') -> middleware('verifiedUser');  //[66]
        route::get('category/{slug}','CategoryController@productsBySlug') ->name('category');     //[68]
        Route::get('product/{slug}', 'ProductController@productsBySlug')->name('product.details');
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


Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {

    Route::post('wishlist', 'WishlistController@store')->name('wishlist.store');

    Route::delete('wishlist', 'WishlistController@destroy')->name('wishlist.destroy');
    Route::get('wishlist/products', 'WishlistController@index')->name('wishlist.products.index');
});



