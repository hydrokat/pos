<?php

Route::get('/', function() {
    if (!Auth::check()) {
        return View::make('pages.login');
    } else {
        return Redirect::to('/dashboard');
    }
});

Route::get('/login-page', function() {
    if (!Auth::check()) {
        return View::make('pages.login');
    } else {
        return Redirect::to('/dashboard');
    }
});

Route::get('/about', function() {
    return View::make('pages.about');
});

Route::group(array('before' => 'csrf'), function() {
    Route::post('/login', array('as' => 'login', 'uses' => 'UserController@login'));
});

Route::group(array('before' => 'auth'), function() {

    Route::get('/acct/chpw', array('before' => 'auth.owner', function() {
        return View::make('pages.chpw');
    }));

    Route::get('/logout', function() {
        $action = "A user has logged out.";
        AppLog::insertLog(Auth::user() -> username, $action);
        Session::flush();
        Auth::logout();
        return Redirect::to('/');
    });

    Route::group(array('before' => 'branch'), function() {
        Route::get('/dashboard',array('as' => 'dashboard', 'uses' => 'HomeController@showDashboard'));

        Route::get('/sale', array('as' => 'sale', 'uses' => 'SalesController@getSale'));

        Route::get('/oldsale', function() {
            return View::make('pages.oldsale');
        });

        Route::get('/transfer', array('as' => 'getTransfer', 'uses' => 'ItemsController@getTransfer'));

        Route::get('/reports/monthly', function() {
            return View::make('pages.monthlyReport');
        });

        Route::get('/items',array('as' => 'items', 'uses' => 'ItemsController@getItems'));
        Route::get('/pcodes',array('as' => 'codes', 'uses' => 'ItemsController@getPCodes'));

        Route::get('/backup', function() {
            return View::make('pages.backup');
        });

        Route::get('/reports/sale/', array('as' => 'sales-report', 'uses' => 'ReportsController@getSalesReport'));
        Route::get('/reports/ending', array('as' => 'ending-report', 'uses' => 'ReportsController@getEndingInventory'));
        Route::get('/reports/transfers', array('as' => 'trans-report', 'uses' => 'ReportsController@getTransfers'));

        Route::get('/reports/inv', array('as' => 'inv-report', 'uses' => 'ReportsController@getInventoryReport'));
        Route::get('/reports/dailysalesgraphdata', array('as' => 'sales-daily-graph-data', 'uses' => 'ReportsController@getDailySales'));
    });

    Route::group(array('before' => 'csrf'), function() {
        Route::post('/sale/new', array('as' => 'newSale', 'uses' => 'SalesController@postSale'));
        Route::post('/sale/cart', array('as' => 'getCart', 'uses' => 'SalesController@getCart'));
        Route::post('/sale/remove', array('as' => 'removeSale', 'uses' => 'SalesController@removeSaleData'));
        Route::post('/sale/confirm', array('as' => 'confirmSale', 'uses' => 'SalesController@confirmSale'));

        Route::post('/item/new', array('as' => 'newItem', 'uses' => 'ItemsController@addItem'));
        Route::post('/item/edit', array('as' => 'editItem', 'uses' => 'ItemsController@editItem'));
        Route::get('/item/delete/{code}/{lot}/{exp}', array('as' => 'delItem', 'uses' => 'ItemsController@deleteItem'));
        Route::get('/item/undodelete/{code}/{lot}/{exp}', array('as' => 'undoDelItem', 'uses' => 'ItemsController@undoDeleteItem'));

        Route::post('/transfer/do', array('as' => 'transfer-item', 'uses' => 'ItemsController@transferItem'));

        Route::post('/chpw/do', array('as' => 'chpw', 'uses' => 'UserController@chpw'));

        Route::post('/acct/create', array('as' => 'post-create-account', 'uses' => 'UserController@postCreate'));

        Route::post('/branches/add', array('as' => 'post-addBranch', 'uses' => 'SettingsController@addBranch'));

        Route::post('/supplier/add', array('as' => 'post-addSupplier', 'uses' => 'SettingsController@addSupplier'));
    });

    /*Invoicing*/
    Route::get('/invoice/', array('as' => 'get-invoice', 'uses' => 'InvoiceController@getInvoice'));
    Route::get('/invoice/{inv}', array('as' => 'invoice', 'uses' => 'InvoiceController@generateInvoice'));
    Route::get('/dinvoice/', array('as' => 'get-dinvoice', 'uses' => 'InvoiceController@getDinvoice'));
    Route::get('/dinvoice/{inv}', array('as' => 'dinvoice', 'uses' => 'InvoiceController@generateDinvoice'));

    /*
    God mode

    God mode is filtered. God mode is for Role 1 only.
    */
    Route::get('/admin/logs', array('as' => 'view-logs', 'uses' => 'GodController@getLogs'));
    Route::get('/admin/logs/date', array('as' => 'post-logs', 'uses' => 'GodController@postLogs'));

    /*
    Owner mode

    Owner mode is for Role 2 and 1 only.
    */
    Route::get('/acct/create', array('as' => 'create-account', 'uses' => 'UserController@getCreate'));

    /* Ajax */
    Route::get('/item/{code}/{lot}/{exp}', array('as' => 'getItem', 'uses' => 'ItemsController@ajaxItem'));
    Route::get('/item/{code}', array('as' => 'getItem', 'uses' => 'ItemsController@ajaxItem'));
    Route::post('/item/postitem', array('as' => 'getItemWithPost', 'uses' => 'ItemsController@ajaxItem'));

    /* Settings */

    Route::get('/settings', array('as' => 'get-settings', 'uses' => 'SettingsController@getSettings'));
    Route::group(array('before' => 'csrf'), function() {
        Route::post('/settings/set-branch', array('as' => 'set-branch', 'uses' => 'SettingsController@setBranch'));
    });
});