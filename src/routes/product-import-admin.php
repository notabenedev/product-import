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

    Route::get("ymls-progress/{file}", "ImportYmlController@progress")
        ->name("ymls.progress");

    Route::resource("ymls", "ImportYmlController")
        ->only(["index","show","destroy"]);
    });
    Route::put("ymls/{file}", "ImportYmlController@run")
        ->name("ymls.run");

    Route::put("ymls-other/{file}", "ImportYmlController@other")
        ->name("ymls.other");




});
