<?php


Route::get('/login', 'Site\SiteAuthController@login')->name('auth.login');
Route::post('/login', 'Site\SiteAuthController@postLogin')->name('auth.postLogin');

Route::get('/', 'Site\SiteHomeController@home')->name('home');

Route::namespace('Site')->name('site.')->group(function () {
    require "site.php";
});

Route::namespace('Api')->name('api.')->group(function () {
    Route::prefix('api')->group(function () {
        require "api.php";
    });
    
});
