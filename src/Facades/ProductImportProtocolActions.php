<?php

namespace Notabenedev\ProductImport\Facades;

use Illuminate\Support\Facades\Facade;
use Notabenedev\ProductImport\Helpers\ProductImportProtocolActionsManager;

/**
 *
 * Class ProductImportProtocolActions
 * @package Notabenedev\ProductImport\Facades
 *
 * @method void manualInit($manualMode = false)
 * @method string init()
 * @method bool|false|string answer(string $value)
 * @method bool|false|string translateAnswer(string $value)
 * @method bool|false|string failure(string $details = "")
 *
 * @see ProductImportProtocolActionsManager
 */
class ProductImportProtocolActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-protocol-actions";
    }
}