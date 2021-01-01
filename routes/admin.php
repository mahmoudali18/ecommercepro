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
        Route::get('logout', 'LoginController@logout')->name('admin.logout');  // [23]

        Route::group(['prefix' => 'settings'], function () {      // [16]
            Route::get('shipping-methods/{type}', 'SettingsController@editShippingMethods')->name('edit.shippings.methods');      // [16]    view edit[21]
            Route::put('shipping-methods/{id}', 'SettingsController@updateShippingMethods')->name('update.shippings.methods');      // [16] [21]
        });

        Route::group(['prefix' => 'profile'], function () {      // [25]
            Route::get('edit', 'ProfileController@editProfile')->name('edit.profile');      // [25]
            Route::put('update', 'ProfileController@updateProfile')->name('update.profile');     // [25] [ مش هباصي الid علشان هجيبه من الauth ]
        });

        ################################     categories Route     ##################################
        Route::group(['prefix' => 'main_categories'], function () {      // [27]
            Route::get('/', 'MainCategoriesController@index')->name('admin.maincategories');                         // [27]
            Route::get('create', 'MainCategoriesController@create')->name('admin.maincategories.create');            //[28]
            Route::post('store', 'MainCategoriesController@store')->name('admin.maincategories.store');               //[29]
            Route::get('edit/{id}', 'MainCategoriesController@edit')->name('admin.maincategories.edit');             //[27]
            Route::post('update/{id}', 'MainCategoriesController@update')->name('admin.maincategories.update');        //[27]
            Route::get('delete/{id}', 'MainCategoriesController@destroy')->name('admin.maincategories.delete');        //[28]
        });
        ################################  end   categories Route     ################################

        ################################    sub categories Route     ##################################
        Route::group(['prefix' => 'sub_categories'], function () {      // [30]
            Route::get('/', 'SubCategoriesController@index')->name('admin.subcategories');                         // []
            Route::get('create', 'SubCategoriesController@create')->name('admin.subcategories.create');            //[31]
            Route::post('store', 'SubCategoriesController@store')->name('admin.subcategories.store');               //[31]
            Route::get('edit/{id}', 'SubCategoriesController@edit')->name('admin.subcategories.edit');             //[31]
            Route::post('update/{id}', 'SubCategoriesController@update')->name('admin.subcategories.update');        //[31]
            Route::get('delete/{id}', 'SubCategoriesController@destroy')->name('admin.subcategories.delete');        //[]
        });
        ################################  end  sub categories Route     ################################


    });


    //route for non auth  "guest"
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin','prefix'=>'admin'], function () {
        Route::get('login', 'LoginController@login')->name('admin.login');   // [9]
        Route::post('login', 'LoginController@postLogin')->name('admin.post.login');   // [10]
    });


});
