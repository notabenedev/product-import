<?php

namespace Notabenedev\ProductImport\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * Class ImportProtocolActions
 * @package Notabenedev\ProductImport\Facades
 *
 * @method static string init()
 */
class ProductImportProtocolActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-protocol-actions";
    }
}