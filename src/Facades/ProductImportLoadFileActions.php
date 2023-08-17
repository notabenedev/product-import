<?php

namespace Notabenedev\ProductImport\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * Class ProductImportLoadFileActions
 * @package Notabenedev\ProductImport\Facades
 *

 */
class ProductImportLoadFileActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-load-file-actions";
    }
}