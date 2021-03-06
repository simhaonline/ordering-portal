<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AddressController;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'has.customer', 'terms']], static function () {
    /*
     *  Home Page
     */
    Route::get('/', 'HomeController@index')->name('home');

    /*
     *  Product Pages
     */
    Route::group(['prefix' => 'products'], static function () {
        Route::get('view/{product}', 'ProductController@show')->name('products.show');
        Route::get('search', 'ProductController@search')->name('products.search');

        Route::get('autocomplete/{search}', static function ($search) {
            return App\Models\Product::autocomplete($search);
        })->name('products.autocomplete');

        Route::get('{cat1?}/{cat2?}/{cat3?}', 'ProductController@index')->name('products');
    });

    Route::group(['prefix' => 'category'], static function () {
        Route::get('image/{products}', static function ($products) {
            return App\Models\Product::checkImage($products);
        })->name('products.check-image');
    });

    /*
     * Customer code auto complete.
     */
    Route::post('customer/autocomplete', static function (Request $request) {
        return Customer::autocomplete($request->customer);
    })->name('customer.autocomplete');

    /*
     * Order Tracking
     */
    Route::group(['prefix' => 'order-tracking'], static function () {
        Route::get('/', 'OrderTrackingController@index')->name('order-tracking');
        Route::get('order/{order}', 'OrderTrackingController@show')->name('order-tracking.show');
        Route::get('copy', 'OrderTrackingController@copy')->name('order-tracking.copy-to-basket');

        Route::get('order/{order}/pdf', 'OrderTrackingController@orderDetailsPDF')->name('order-tracking.pdf');
        Route::get('invoice/{order}/{customer_order}/{download?}', 'OrderTrackingController@invoicePdf')->name('order-tracking.invoice-pdf');
    });

    /*
     * Order Upload
     */
    Route::group(['prefix' => 'upload'], static function () {
        Route::get('/', 'UploadController@index')->name('upload');
        Route::post('validation', 'UploadController@validation')->name('upload-validate');
        Route::get('completed', 'UploadController@store')->name('upload-completed');
    });

    /*
     * Saved Baskets
     */
    Route::group(['prefix' => 'saved-baskets'], static function () {
        Route::get('/', 'SavedBasketController@index')->name('saved-baskets');
        Route::get('view', 'SavedBasketController@show')->name('saved-baskets.show');
        Route::post('store', 'SavedBasketController@store')->name('saved-baskets.store');
        Route::get('delete', 'SavedBasketController@destroy')->name('saved-baskets.destroy');
        Route::get('copy', 'SavedBasketController@copyToBasket')->name('saved-baskets.copy');
    });

    /*
     * Reports
     */
    Route::group(['prefix' => 'reports'], static function () {
        Route::get('/', 'ReportController@index')->name('reports');
        Route::post('show', 'ReportController@show')->name('reports.show');
    });

    /*
     * Support pages
     */
    Route::group(['prefix' => 'support'], static function () {
        Route::get('terms-and-conditions', [
            'uses' => 'SupportController@index',
            'page' => 'terms-and-conditions',
        ])->name('support.terms');
        Route::get('accessibility-policy', [
            'uses' => 'SupportController@index',
            'page' => 'accessibility-policy',
        ])->name('support.accessibility');
        Route::get('data-protection', [
            'uses' => 'SupportController@index',
            'page' => 'data-protection',
        ])->name('support.data');
    });

    /*
     * Product Data
     */
    Route::group(['prefix' => 'product-data'], static function () {
        Route::get('/', 'ProductDataController@index')->name('product-data');
        Route::get('/range/{brand}', static function ($brand) {
            return Category::range($brand);
        })->name('product-data.search-range');

        Route::get('prices', 'ProductDataController@prices')->name('product-data.prices');
        Route::get('data', 'ProductDataController@data')->name('product-data.data');
    });

    /*
     * User Account
    */
    Route::group(['prefix' => 'account'], static function () {
        Route::get('/', 'AccountController@index')->name('account');
        Route::post('/', 'AccountController@store')->name('account.store');

        Route::group(['prefix' => 'addresses'], static function () {
            Route::get('/', 'AddressController@index')->name('account.addresses');
            Route::get('create', 'AddressController@create')->name('account.address.create');
            Route::post('/', 'AddressController@store')->name('account.address.store');
            Route::get('{id}/edit', 'AddressController@edit')->name('account.address.edit');
            Route::patch('{id}/edit', 'AddressController@update')->name('account.address.update');
            Route::post('default', 'AddressController@default')->name('account.address.default');
            Route::get('{id}/delete', 'AddressController@destroy')->name('account.address.destroy');
            Route::get('select/{id?}', 'AddressController@select')->name('account.address.select');

            Route::get('lookup', [AddressController::class, 'lookup'])->name('account.address.lookup');
        });

        Route::group(['prefix' => 'customer'], static function () {
            Route::post('change', 'AccountController@changeCustomer')->name('customer.change');
            Route::get('revert', 'AccountController@revertChangeCustomer')->name('customer.change.revert');
        });
    });

    /*
     * Basket
     */
    Route::group(['prefix' => 'basket'], static function () {
        Route::get('/', 'BasketController@index')->name('basket');
        Route::get('empty', 'BasketController@clear')->name('basket.empty');

        Route::post('add-product', 'BasketController@addProduct')->name('basket.add-product');
        Route::post('delete-product', 'BasketController@removeProduct')->name('basket.delete-line');

        Route::get('summary/{shipping_code?}', static function ($shipping_code = null) {
            return Basket::show($shipping_code);
        })->name('basket.summary');

        Route::get('dropdown', static function () {
            return Basket::show();
        })->name('basket.dropdown');
    });

    /*
     * Checkout
     */
    Route::group(['prefix' => 'checkout'], static function () {
        Route::get('/', 'CheckoutController@index')->name('checkout');
        Route::post('order', 'CheckoutController@store')->name('checkout.order');
        Route::get('confirmation', 'CheckoutController@confirmation')->name('checkout.confirmation');
    });

    /* Contact Page */
    Route::group(['prefix' => 'contact'], static function () {
        Route::get('/', 'ContactController@index')->name('contact');
        Route::post('/', 'ContactController@store')->name('contact.email');
    });

    Route::group(['prefix' => 'terms'], static function () {
        Route::get('/', static function () {
            return view('support.site-terms');
        })->name('site-terms');

        Route::post('/', static function () {
            auth()->user()->acceptTerms();

            return redirect(route('home'));
        })->name('site-terms.accept');
    });
});

Auth::routes(['register' => false]);

Route::get('not-supported', static function () {
    return view('errors.not-supported');
})->name('not-supported');

Route::fallback(static function () {
    return view('errors.404');
});

Route::get('product_images/{image}', static function ($image) {
    if (\Storage::disk('s3')->exists($image)) {
        return response()->make(\Storage::disk('s3')->get($image), 200, [
            'content-disposition' => 'inline',
            'Content-Type' => 'image/png',
        ]);
    }

    abort(404);
});
