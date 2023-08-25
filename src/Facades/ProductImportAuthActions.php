<?php

namespace Notabenedev\ProductImport\Facades;

use App\ImportYml;
use Illuminate\Support\Facades\Facade;
use Notabenedev\ProductImport\Helpers\ProductImportAuthActionsManager;

/**
 *
 * Class ProductImportAuthActions
 * @package Notabenedev\ProductImport\Facades
 *
 * @method string getCookieName()
 * @method bool checkRequestUser()
 * @method  bool checkAuthUser()
 * @method static ImportYml|string getUserCookie()
 *
 * @see ProductImportAuthActionsManager
 */
class ProductImportAuthActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-auth-actions";
    }
}