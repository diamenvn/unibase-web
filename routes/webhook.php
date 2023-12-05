<?php

/// Store
Route::middleware('auth.site')->prefix('tiktok/v1/webhook')->group(function () {
    Route::get('/', 'WebhookTiktokController@callback')->name('tiktok.callback');
});