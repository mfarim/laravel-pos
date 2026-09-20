<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| POS SaaS Mobile REST API Routes (V1)
|--------------------------------------------------------------------------
|
| Version 1 of the REST API designed for Mobile POS (React Native, Flutter,
| Android, iOS), Tablets, and External POS integrations.
|
*/

Route::prefix('v1')->namespace('Api\V1')->group(function () {

    // 1. Public Authentication Endpoints (Rate Limited)
    Route::post('auth/login', 'AuthController@login')->middleware('throttle:10,1');
    Route::post('auth/pin-login', 'AuthController@pinLogin')->middleware('throttle:5,1');

    // 2. Authenticated Endpoints (Bearer Token + Tenant Scope)
    Route::middleware(['auth.api_token', 'tenant.scope'])->group(function () {

        // User & Auth Management
        Route::get('auth/me', 'AuthController@me');
        Route::post('auth/logout', 'AuthController@logout');

        // Outlets
        Route::get('outlets', 'OutletController@index');
        Route::get('outlets/{id}', 'OutletController@show');

        // Catalog: Categories & Products
        Route::get('categories', 'CategoryController@index');
        Route::get('categories/{id}/products', 'CategoryController@products');
        Route::get('products', 'ProductController@index');
        Route::get('products/barcode/{code}', 'ProductController@barcode');
        Route::get('products/{id}', 'ProductController@show');

        // Payment Methods
        Route::get('payment-methods', 'PaymentMethodController@index');

        // Cashier Shift Sessions
        Route::get('sessions/current', 'SessionController@current');
        Route::get('sessions/{id}/report', 'SessionController@report');
        Route::post('sessions/close', 'SessionController@close');

        // Cart Calculations (Read-only simulation)
        Route::post('orders/calculate', 'TransactionController@calculate');

        // Transaction History & Receipts
        Route::get('transactions', 'TransactionController@index');
        Route::get('transactions/{id}', 'TransactionController@show');

        // Held Orders (Hold / Recall Cart)
        Route::get('held-orders', 'HeldOrderController@index');
        Route::post('held-orders', 'HeldOrderController@store');
        Route::delete('held-orders/{id}', 'HeldOrderController@destroy');

        // Supervisor PIN Authorization Override
        Route::post('authorization/verify-pin', 'AuthorizationController@verifyPin');

        // Modifying Transaction Actions (Blocked if Tenant Subscription is Frozen)
        Route::middleware(['subscription.block_frozen'])->group(function () {
            Route::post('sessions/open', 'SessionController@open');
            Route::post('orders/checkout', 'TransactionController@checkout');
            Route::post('transactions/{id}/void', 'TransactionController@voidTransaction');
            Route::post('sync/transactions', 'MobileSyncController@syncTransactions');
        });
    });
});
