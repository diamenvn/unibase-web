<?php
Route::middleware('auth.site')->group(function () {
    Route::get('/logout', 'SiteAuthController@logout')->name('auth.logout');

    Route::get('/dashboard', 'SiteHomeController@dashboard')->name('home.dashboard');
    //orders
    Route::get('/orders/list-all', 'SiteOrderController@list')->name('order.list');
    Route::get('/orders/detail/{id}', 'SiteOrderController@detail')->name('order.detail');
    Route::get('/orders/my-list', 'SiteOrderController@myList')->name('order.mylist');
    Route::get('/orders/customer-care', 'SiteOrderController@customerCare')->name('care');

    Route::get('/orders/create', 'SiteOrderController@create')->name('order.create');

    //lading
    Route::get('/ladings/list-all', 'SiteOrderController@lading')->name('lading.list');
    Route::get('/ladings/list-care-all', 'SiteOrderController@care')->name('lading.care');

    //report
    Route::get('/reports/billing', 'SiteReportController@billing')->name('report.billing');
    Route::get('/reports/billing-for-sale', 'SiteReportController@billingSale')->name('report.billingSale');
    Route::get('/reports/for-personal', 'SiteReportController@personal')->name('report.personal');
    Route::get('/reports/note', 'SiteReportController@note')->name('report.note');

    //User
    Route::get('/users/create', 'SiteUserController@create')->name('user.create');

    //settings
    Route::get('/setting/group', 'SiteSettingController@group')->name('setting.group');
    Route::get('/setting/add-product', 'SiteSettingController@addProduct')->name('setting.addProduct');
    Route::get('/setting/import/api', 'SiteSettingController@importFromAPI')->name('setting.import.api');
});
?>