<?php

use Illuminate\Support\Facades\Route;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\ProductImport\Api',
    //'middleware' => ['web', 'management'],
    'as' => 'api.',
    'prefix' => 'api/product-import',
], function () {
    Route::match(["get", "post"], "/", function () {
        return ProductImportProtocolActions::init();
    });
});

