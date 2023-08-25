<?php

namespace Notabenedev\ProductImport\Facades;

use App\ImportYml;
use App\YmlFile;
use Illuminate\Support\Facades\Facade;
use Notabenedev\ProductImport\Helpers\ProductImportParserActionsManager;

/**
 *
 * Class ProductImportParserActions
 * @package Notabenedev\ProductImport\Facades
 *
 * @method void parseFile(YmlFile $file)
 * @method string initParseFile(ImportYml $yml)
 * @method \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany getYmlFile(ImportYml $yml, $original)
 * @method string checkProgress(YmlFile $file)
 * @method int getJobsCount()
 * @method string findCategoryByUUid(string $uuid)
 * @method static void otherCategories($ymlFileId)
 *
 * @see ProductImportParserActionsManager
 */
class ProductImportParserActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-import-parser-actions";
    }
}