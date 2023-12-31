<?php


Route::middleware('auth.api')->group(function () {
    //Home
    Route::get('/home/get-report', 'ApiHomeController@reportHome')->name('home.report');

    //Order
    Route::get('/order/get-list-order', 'ApiOrderController@getAllListOrder')->name('order.getAllListOrder');
    Route::post('/order/detail/save/{id}', 'ApiOrderController@saveDetail')->name('order.detail.save');
    Route::post('/order/create', 'ApiOrderController@saveOrder')->name('order.create.save');
    Route::post('/order/assign', 'ApiOrderController@saveAssign')->name('order.assign.save');
    Route::get('/order/get-list-label', 'ApiOrderController@getListLabel')->name('order.getListLabel');

    Route::post('/order/removeOrder', 'ApiOrderController@removeOrder')->name('order.removeOrder');
    Route::post('/order/searchPhone', 'ApiOrderController@searchPhone')->name('order.searchPhone');
    
    Route::get('/order/get-my-list-order', 'ApiOrderController@getAllListMyOrder')->name('order.getAllListMyOrder');
    Route::get('/order/export-excel-lading', 'ApiExportController@exportExcelLading')->name('order.export.excel.lading');
    Route::get('/order/export-excel-order', 'ApiExportController@exportExcelOrder')->name('order.export.excel');
    Route::post('/order/choose-customer', 'ApiOrderController@chooseCustomer')->name('order.choose-customer');
    Route::post('/order/status/next-step/{id}', 'ApiOrderController@nextStep')->name('order.next-step');
    Route::post('/order/status/cancel-step/{id}', 'ApiOrderController@cancelStep')->name('order.cancel-step');
    //Info 

    Route::get('/info/provin', 'ApiInfoController@getProvin')->name('info.getProvin');
    Route::get('/info/district', 'ApiInfoController@getDistrict')->name('info.getDistrict');
    Route::get('/info/town', 'ApiInfoController@getTown')->name('info.getTown');

    //Care
    Route::get('/order/get-list-order-care', 'ApiOrderCareController@getAllListOrder')->name('order.lists.care');

    //Lading
    // Route::get('/lading/get-list', 'ApiLadingController@getList')->name('lading.getList');
    // Route::get('/lading/detail/{id}', 'ApiLadingController@getDetail')->name('lading.getDetail');
    // Route::post('/lading/detail/save/{id}', 'ApiLadingController@saveDetail')->name('lading.detail.save');
    Route::get('/lading/get-list', 'ApiLadingController@getAllListLading')->name('lading.getList');
    Route::get('/lading/get-list-care', 'ApiLadingController@getAllListLadingCare')->name('lading.getListCare');
    Route::post('/lading/updateStatus', 'ApiLadingController@updateStatus')->name('lading.updateStatus');
    Route::post('/lading/updateStatusOrderCare', 'ApiLadingController@updateStatusOrderCare')->name('lading.updateStatusOrderCare');

    //User
    Route::get('/user/detail/{id}', 'ApiUserController@getDetail')->name('user.getDetail');
    Route::post('/user/detail/save/{id}', 'ApiUserController@saveDetail')->name('user.detail.save');
    Route::post('/user/create/save', 'ApiUserController@saveCreate')->name('user.create.save');
    Route::post('/user/remove/{id}', 'ApiUserController@removeUser')->name('user.remove');
    // Report
    Route::get('/report/get-all-billing', 'ApiReportController@getAllBilling')->name('report.getAllBilling');
    Route::get('/report/get-for-personal', 'ApiReportController@getForPersonal')->name('report.getForPersonal');
    Route::get('/report/billing-for-sale', 'ApiReportController@reportBillingForSale')->name('report.reportBillingForSaleFromMkt');
    Route::get('/report/note', 'ApiReportController@note')->name('report.note');

    
    // Setting
    Route::post('/setting/create-group', 'ApiSettingController@createGroup')->name('setting.createGroup');
    Route::post('/setting/update-group/{id}', 'ApiSettingController@updateGroup')->name('setting.updateGroup');

    Route::post('/setting/create-product', 'ApiSettingController@saveAddProduct')->name('setting.saveAddProduct');
    Route::post('/setting/hidden-product', 'ApiSettingController@hiddenProduct')->name('setting.hiddenProduct');

    Route::post('/setting/import/api', 'ApiSettingController@saveImportFromAPI')->name('setting.import.api.save');
    Route::get('/setting/import/api', 'ApiSettingController@getListAPIImport')->name('setting.import.api');
    Route::get('/setting/import/api/{id}', 'ApiSettingController@getDetailtAPIImport')->name('setting.import.api.detail');
    Route::post('/setting/import/api/edit/{id}', 'ApiSettingController@editImportFromAPI')->name('setting.import.edit');
    Route::post('/setting/import/api/remove/{id}', 'ApiSettingController@removeImportFromAPI')->name('setting.import.remove');


    //upload
    Route::post('/upload', 'ApiUploadController@upload')->name('upload');
});

//Product
Route::middleware('auth.api')->prefix('product')->group(function () {
    Route::post('/store', 'ApiProductController@store')->name('product.store');
    Route::get('/get-list-product', 'ApiProductController@getListProduct')->name('product.getListProduct');
});

//Customer
Route::middleware('auth.api')->prefix('customer')->group(function () {
    Route::post('/store', 'ApiCustomerController@store')->name('customer.store');
    Route::get('/get-list-customer', 'ApiCustomerController@lists')->name('customer.getListCustomer');
    Route::post('/searchPhone', 'ApiCustomerController@searchPhone')->name('customer.searchPhone');
    Route::post('/update/{id}', 'ApiCustomerController@update')->name('customer.update');
    Route::post('/remove', 'ApiCustomerController@remove')->name('customer.remove');
    Route::post('/update_activity/{id}', 'ApiCustomerController@updateActivity')->name('customer.update_activity');
    
});

//Store
Route::middleware('auth.api')->prefix('store')->group(function () {
    Route::post('/store', 'ApiStoreController@store')->name('store.store');
    Route::get('/get-list-store', 'Store\ApiStoreController@list')->name('store.list');
    Route::post('/update-setting-condition', 'Store\ApiStoreController@updateSettingCondition')->name('store.update-setting-condition');
});

Route::get('/token/order/create', 'ApiTokenController@create')->name('token.order.create.save');
Route::get('/token/order/add', 'ApiOrderController@tokenSaveOrder')->name('token.order.create.save');
Route::post('/createOrderFromLadipage/{id}', 'ApiSettingController@pushDataImportFromAPI')->name('setting.import.api.push');
?>

