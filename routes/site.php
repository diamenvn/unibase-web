<?php
Route::middleware('auth.site')->group(function () {
    Route::get('/logout', 'SiteAuthController@logout')->name('auth.logout');

    Route::get('/welcome', 'SiteHomeController@welcome')->name('home.welcome');
    Route::get('/dashboard', 'SiteHomeController@dashboard')->name('home.dashboard');
    //orders
    Route::get('/order/lists', 'SiteOrderController@list')->name('order.lists');
    Route::get('/order/detail/{id}', 'SiteOrderController@detail')->name('order.detail');
    Route::get('/order/my-list', 'SiteOrderController@myList')->name('order.mylist');
    Route::get('/order/customer-care', 'SiteOrderController@customerCare')->name('care');

    Route::get('/order/create', 'SiteOrderController@create')->name('order.create');

    //lading
    Route::get('/ladings/list-all', 'SiteOrderController@lading')->name('lading.list');
    Route::get('/ladings/list-care-all', 'SiteOrderController@care')->name('lading.care');

    //report
    Route::get('/reports/billing', 'SiteReportController@billing')->name('report.billing');
    Route::get('/reports/billing-for-sale', 'SiteReportController@billingSale')->name('report.billingSale');
    Route::get('/reports/for-personal', 'SiteReportController@personal')->name('report.personal');
    Route::get('/reports/note', 'SiteReportController@note')->name('report.note');

    //User
    Route::get('/users/lists', 'SiteUserController@list')->name('user.lists');

    //settings
    Route::get('/setting/group', 'SiteSettingController@group')->name('setting.group');
    Route::get('/setting/add-product', 'SiteSettingController@addProduct')->name('setting.addProduct');
    Route::get('/setting/import/api', 'SiteSettingController@importFromAPI')->name('setting.import.api');
});


/// Product
Route::middleware('auth.site')->prefix('product')->group(function () {
    Route::get('/create', 'SiteProductController@create')->name('product.create');
    Route::get('/detail/{id}', 'SiteProductController@detail')->name('product.detail');
    Route::get('/lists', 'SiteProductController@list')->name('product.lists');
});

/// Customer
Route::middleware('auth.site')->prefix('customer')->group(function () {
    Route::get('/create', 'SiteCustomerController@create')->name('customer.create');
    Route::get('/detail/{id}', 'SiteCustomerController@detail')->name('customer.detail');
    Route::get('/lists', 'SiteCustomerController@list')->name('customer.lists');
});
?>