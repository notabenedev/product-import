<?php

namespace Notabenedev\ProductImport\Facades;

use App\ImportYml;
use Illuminate\Support\Facades\Facade;

/**
 *
 * Class ProductImportProtocolActions
 * @package Notabenedev\ProductImport\Facades
 *
 * @method static bool checkRequestUser()
 * @method static bool|string checkAuthUser()
 * @method static \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response setUserCookie($value)
 * @method static ImportYml|string getUserCookie()
 * @method static string getCookieName()
 */
class ProductImportAuthActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-auth-actions";
    }
}