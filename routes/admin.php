<?php

use Illuminate\Support\Facades\Route;


define('PAGINATION_COUNT',10);

Route::group(['namespace' => 'Admin','middleware' => 'auth:admin'],function (){

Route::get('/','DashboardController@Dashboard')->name('admin.dashboard');
################ Begin Languages Routes ####################
    Route::group(['prefix'=>'languages'],function (){
    Route::get('/','languageController@index')->name('admin.languages');
    Route::get('create','languageController@create')->name('admin.languages.create');
    Route::post('store','languageController@store')->name('admin.languages.store');
    Route::get('edit/{id}','languageController@edit')->name('admin.languages.edit');
    Route::post('update/{id}','languageController@update')->name('admin.languages.update');
    Route::get('delete/{id}','languageController@destroy')->name('admin.languages.delete');

    });
    ################ End Languages Routes ####################

    ################ Begin Main Categories Routes ####################
    Route::group(['prefix'=>'main_categories'],function (){
        Route::get('/','MainCategoriesController@index')->name('admin.maincategories');
        Route::get('create','MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('store','MainCategoriesController@store')->name('admin.maincategories.store');
        Route::get('edit/{id}','MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::post('update/{id}','MainCategoriesController@update')->name('admin.maincategories.update');
        Route::get('delete/{id}','MainCategoriesController@destroy')->name('admin.maincategories.delete');

    });
    ################ End Main Categories Routes ####################

    ################ Begin VENDOR Routes ####################
    Route::group(['prefix'=>'vendors'],function (){
        Route::get('/','VendorController@index')->name('admin.vendors');
        Route::get('create','VendorController@create')->name('admin.vendors.create');
        Route::post('store','VendorController@store')->name('admin.vendors.store');
        Route::get('edit/{id}','VendorController@edit')->name('admin.vendors.edit');
        Route::post('update/{id}','VendorController@update')->name('admin.vendors.update');
        Route::get('delete/{id}','VendorController@destroy')->name('admin.vendors.delete');

    });
    ################ End vendorRoutes ####################

});

Route::group(['namespace' => 'Admin','middleware' => 'guest:admin'],function (){
    Route::get('login' , 'LoginController@getlogin')->name('get.admin.login');
    Route::post('login' , 'LoginController@login')->name('admin.login');
});

