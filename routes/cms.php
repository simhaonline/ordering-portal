<?php

use App\Models\Customer;
use App\Models\HomeLink;
use App\Models\User;
use App\Models\UserCustomer;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your CMS routes
| for the administration area of the website. Located on the cms. sub-domain.
|
*/

Route::get('login', 'Auth\AdminController@showLoginForm')->name('cms.login');
Route::post('login', 'Auth\AdminController@login')->name('cms.login.submit');

Route::group(['middleware' => 'auth:admin'], static function () {

    Route::post('logout', 'Auth\AdminController@logout')->name('cms.logout');

    Route::get('/', 'Cms\HomeController@index')->name('cms.index');

    Route::group(['prefix' => 'site-users'], static function () {
        Route::get('/', 'Cms\UserController@index')->name('cms.site-users');

        //Route::get('show/{id}', static function ($id) {
        //    return User::show($id);
        //})->name('cms.site-users.show');

        //Route::post('validate', 'Cms\UserController@validation')->name('cms.site-users.validate');
        Route::post('/', 'Cms\UserController@store')->name('cms.site-users.store');

        Route::delete('extra-customers/', static function () {
            return UserCustomer::destroy(request('id'));
        })->name('cms.extra-customers.destroy');

        Route::post('extra-customers', static function () {
            return UserCustomer::store();
        })->name('cms.extra-customers.store');

        Route::post('password-reset', 'Cms\UserController@passwordReset')->name('cms.site-users.password-reset');

        Route::patch('{id}', 'Cms\UserController@update')->name('cms.site-users.update');
        Route::delete('{id}', 'Cms\UserController@destroy')->name('cms.site-users.destroy');
    });

    Route::group(['prefix' => 'admin-users'], static function () {
        Route::get('/', 'Cms\AdminController@index')->name('cms.admin');
    });

    Route::group(['prefix' => 'company-information'], static function () {
        Route::get('/', 'Cms\CompanyDetailsController@index')->name('cms.company-information');
        Route::post('/', 'Cms\CompanyDetailsController@store')->name('cms.company-information.store');
    });

    Route::group(['prefix' => 'home-links'], static function () {
        Route::get('/', 'Cms\HomeLinksController@index')->name('cms.home-links');
        Route::post('store', 'Cms\HomeLinksController@store')->name('cms.home-links.store');
        Route::post('update', 'Cms\HomeLinksController@updatePositions')->name('cms.home-links.update');
        Route::get('delete/{id}', 'Cms\HomeLinksController@destroy')->name('cms.home-links.delete');

        Route::post('show', static function (Request $request) {
            return HomeLink::show($request->id);
        })->name('cms.home-links.show');
    });

    Route::group(['prefix' => 'contacts'], static function () {
        Route::get('/', 'Cms\ContactController@index')->name('cms.contacts');
        Route::post('/', 'Cms\ContactController@store')->name('cms.contacts.store');
        Route::get('view/{id}', 'Cms\ContactController@show')->name('cms.contacts.show');
        Route::get('delete/{id}', 'Cms\ContactController@destroy')->name('cms.contacts.delete');
    });

    /* Product images */
    Route::group(['prefix' => 'product-images'], static function () {
        Route::get('/', 'Cms\ProductImageController@index')->name('cms.product-images');
        Route::post('check', 'Cms\ProductImageController@missingImages')->name('cms.product-images.check');
        Route::post('store', 'Cms\ProductImageController@store')->name('cms.product-images.store');
    });

    Route::group(['prefix' => 'customer'], static function() {
        Route::get('validate', static function() {
            return Customer::show(request('code'));
        });
    });

});
