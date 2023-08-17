<?php

use Illuminate\Support\Facades\Route;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\ProductImport\Admin',
    'middleware' => ['web','management'],
    'as' => 'admin.',
    'prefix' => 'admin',
], function () {

Route::group([ ], function () {

    Route::post("ymls-load", function () {
        return ProductImportProtocolActions::manualInit("form");
    })->name("ymls.load");

    Route::resource("ymls", "ImportYmlController")
        ->only(["index","show","destroy"]);
    });
    Route::put("ymls/{file}", "ImportYmlController@run")
        ->name("ymls.run");

});
