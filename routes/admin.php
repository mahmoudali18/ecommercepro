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

        ################################    Brands Route     ##################################
        Route::group(['prefix' => 'brands'], function () {      // [33]
            Route::get('/', 'BrandsController@index')->name('admin.brands');                         // [33]
            Route::get('create', 'BrandsController@create')->name('admin.brands.create');            //[33]
            Route::post('store', 'BrandsController@store')->name('admin.brands.store');               //[33]
            Route::get('edit/{id}', 'BrandsController@edit')->name('admin.brands.edit');             //[34]
            Route::post('update/{id}', 'BrandsController@update')->name('admin.brands.update');        //[35]
            Route::get('delete/{id}', 'BrandsController@destroy')->name('admin.brands.delete');        //[35]
        });
        ################################  end  Brands Route     ################################

        ################################    Tags Route     ##################################
        Route::group(['prefix' => 'tags'], function () {      // [36]
            Route::get('/', 'TagsController@index')->name('admin.tags');                         // [37]
            Route::get('create', 'TagsController@create')->name('admin.tags.create');            //[37]
            Route::post('store', 'TagsController@store')->name('admin.tags.store');               //[37]
            Route::get('edit/{id}', 'TagsController@edit')->name('admin.tags.edit');             //[37]
            Route::post('update/{id}', 'TagsController@update')->name('admin.tags.update');        //[37]
            Route::get('delete/{id}', 'TagsController@destroy')->name('admin.tags.delete');        //[37]
        });
        ################################  end  Tags Route     ################################


    });


    //route for non auth  "guest"
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin','prefix'=>'admin'], function () {
        Route::get('login', 'LoginController@login')->name('admin.login');   // [9]
        Route::post('login', 'LoginController@postLogin')->name('admin.post.login');   // [10]
    });


});
