<?php

namespace Notabenedev\ProductImport\Facades;

use App\ImportYml;
use Illuminate\Support\Facades\Facade;
use Notabenedev\ProductImport\Helpers\ProductImportLoadFileActionsManager;

/**
 *
 * Class ProductImportLoadFileActions
 * @package Notabenedev\ProductImport\Facades
 * @method false|mixed checkFileName()
 * @method bool|string modeLoadFile(ImportYml $yml, $isForm = false)
 *
 * @see ProductImportLoadFileActionsManager
 */
class ProductImportLoadFileActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-load-file-actions";
    }
}