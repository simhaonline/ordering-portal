<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\GlobalSettings;
use App\Models\UserCustomer;

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
Route::get('password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('cms.forgot-password');
Route::post('password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('cms.password-email');
Route::get('password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('cms.password.reset');
Route::post('password/reset', 'Auth\AdminResetPasswordController@reset')->name('cms.password.update');

Route::group(['middleware' => 'auth:admin'], static function () {
    Route::post('logout', 'Auth\AdminController@logout')->name('cms.logout');
    Route::get('/', 'Cms\HomeController@index')->name('cms.index');

    Route::group(['prefix' => 'orders'], static function () {
        Route::get('/', 'Cms\OrdersController@index')->name('cms.orders');
        Route::get('re-import/{order_number}', 'Cms\OrdersController@markForImport')->name('cms.orders.import');
    });

    Route::group(['prefix' => 'site-users'], static function () {
        Route::get('/', 'Cms\UserController@index')->name('cms.site-users');
        Route::post('/', 'Cms\UserController@store')->name('cms.site-users.store');
        Route::post('password-reset', 'Cms\UserController@passwordReset')->name('cms.site-users.password-reset');

        Route::delete('extra-customers', static function () {
            return UserCustomer::destroy(request('id'));
        })->name('cms.extra-customers.destroy');

        Route::post('extra-customers', static function () {
            return UserCustomer::store();
        })->name('cms.extra-customers.store');

        Route::patch('{id}', 'Cms\UserController@update')->name('cms.site-users.update');
        Route::delete('{id}', 'Cms\UserController@destroy')->name('cms.site-users.destroy');
    });

    Route::group(['prefix' => 'admin-users'], static function () {
        Route::get('/', 'Cms\AdminController@index')->name('cms.admin-users');
        Route::post('/', 'Cms\AdminController@store')->name('cms.admin-users.store');
        Route::delete('{id}', 'Cms\AdminController@destroy')->name('cms.admin-users.destroy');
        Route::patch('{id}', 'Cms\AdminController@update')->name('cms.admin-users.update');
    });

    Route::group(['prefix' => 'company-information'], static function () {
        Route::get('/', 'Cms\CompanyDetailsController@index')->name('cms.company-information');
        Route::post('/', 'Cms\CompanyDetailsController@store')->name('cms.company-information.store');
    });

    Route::group(['prefix' => 'contacts'], static function () {
        Route::get('/', 'Cms\ContactController@index')->name('cms.contacts');
        Route::post('/', 'Cms\ContactController@store')->name('cms.contacts.store');
        Route::patch('{contact}', 'Cms\ContactController@update')->name('cms.contacts.update');
        Route::delete('{contact}', 'Cms\ContactController@destroy')->name('cms.contacts.delete');
    });

    Route::group(['prefix' => 'pages'], static function () {
        Route::get('data-protection', 'Cms\PagesController@dataProtection')->name('cms.pages.data-protection');
        Route::get('terms', 'Cms\PagesController@terms')->name('cms.pages.terms');
        Route::get('accessibility', 'Cms\PagesController@accessibility')->name('cms.pages.accessibility');

        Route::post('/', 'Cms\PagesController@store')->name('cms.pages.store');
    });

    Route::group(['prefix' => 'site-settings'], static function () {
        Route::get('/', 'Cms\SiteSettingsController@index')->name('cms.site-settings');
        Route::patch('/', 'Cms\SiteSettingsController@update')->name('cms.site-settings.update');

        Route::patch('maintenance', static function () {
            return GlobalSettings::where('key', 'maintenance')->update([
                'value' => json_encode(['enabled' => request('enabled'), 'message' => request('message')], true),
            ]);
        })->name('cms.site-settings.maintenance');
    });

    Route::group(['prefix' => 'home-links'], static function () {
        Route::get('/', 'Cms\HomeLinksController@index')->name('cms.home-links');
        Route::post('/', 'Cms\HomeLinksController@store')->name('cms.home-links.store');
        Route::patch('/update-positions', 'Cms\HomeLinksController@updatePositions')->name('cms.home-links.update');

        Route::post('/categories/{level_1}/{level_2?}/{level_3?}', static function (
            $level_1,
            $level_2 = null,
            $level_3 = null
        ) {
            return Category::showLevels($level_1, $level_2);
        });

        Route::delete('{id}', 'Cms\HomeLinksController@destroy')->name('cms.home-links.delete');
    });

    Route::group(['prefix' => 'small-order'], static function () {
        Route::get('/', 'Cms\SmallOrderChargeController@index')->name('cms.small-order');
        Route::post('/', 'Cms\SmallOrderChargeController@update')->name('cms.small-order.update');
    });

    Route::group(['prefix' => 'discounts'], static function () {
        Route::get('/', 'Cms\DiscountsController@index')->name('cms.discounts');
        Route::post('/', 'Cms\DiscountsController@globalStore')->name('cms.discounts.global-store');
        Route::post('customer', 'Cms\DiscountsController@store')->name('cms.discounts.customer-store');
        Route::delete('customer/{id}', 'Cms\DiscountsController@destroy')->name('cms.discounts.customer-destroy');
    });

    Route::group(['prefix' => 'delivery-methods'], static function () {
        Route::get('/', 'Cms\DeliveryMethodsController@index')->name('cms.delivery-methods');
        Route::post('/', 'Cms\DeliveryMethodsController@store')->name('cms.delivery-methods.store');
        Route::delete('{deliveryMethod}', 'Cms\DeliveryMethodsController@destroy')->name('cms.delivery-methods.delete');

        Route::post('collection-messages', 'Cms\DeliveryMethodsController@storeCollectionMessage')->name('cms.collection-messages.store');
    });

    Route::group(['prefix' => 'promotions'], static function () {
        Route::get('/', 'Cms\PromotionController@index')->name('cms.promotions');
        Route::post('/', 'Cms\PromotionController@store')->name('cms.promotions.store');
        Route::patch('{id}', 'Cms\PromotionController@edit')->name('cms.promotions.update');
        Route::delete('{id}', 'Cms\PromotionController@destroy')->name('cms.promotions.destroy');
    });

    Route::group(['prefix' => 'order-upload'], static function () {
        Route::get('/', 'Cms\OrderUploadController@index')->name('cms.order-upload');
        Route::post('/', 'Cms\OrderUploadController@store')->name('cms.order-upload.store');
    });

    Route::group(['prefix' => 'product-images'], static function () {
        Route::get('/', 'Cms\ProductImageController@index')->name('cms.product-images');
        Route::get('missing', 'Cms\ProductImageController@checkImage')->name('cms.product-images.missing');
        Route::post('/', 'Cms\ProductImageController@store')->name('cms.product-images.store');
    });

    Route::group(['prefix' => 'category-images'], static function () {
        Route::get('/', 'Cms\CategoryImagesController@index')->name('cms.category-images');
        Route::delete('{id}', 'Cms\CategoryImagesController@destroy')->name('cms.category-images.destroy');
        Route::post('store', 'Cms\CategoryImagesController@store')->name('cms.category-images.store');
    });

    Route::group(['prefix' => 'product-data'], static function () {
        Route::get('/', 'Cms\ProductDataController@index')->name('cms.product-data');
        Route::patch('/', 'Cms\ProductDataController@store')->name('cms.product-data.update');
    });

    Route::group(['prefix' => 'customer'], static function () {
        Route::get('validate', static function () {
            return Customer::show(request('code'));
        });
    });

    Route::group(['prefix' => 'activity'], static function () {
        Route::get('/', 'Cms\ActivityController@index')->name('cms.activity');
    });
});
