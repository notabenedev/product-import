<?php

namespace Notabenedev\ProductImport\Helpers;

use App\Category;
use App\ImportYml;
use App\Jobs\Vendor\ProductImport\ProcessOffer;
use App\Jobs\Vendor\ProductImport\ProcessOtherCategory;
use App\Jobs\Vendor\ProductImport\ProcessOtherProduct;
use App\Jobs\Vendor\ProductImport\ProcessProduct;
use App\Product;
use App\YmlFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Notabenedev\ProductImport\Facades\ProductImportLoadFileActions;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;
use App\Jobs\Vendor\ProductImport\ProcessYmlFile;
use App\Jobs\Vendor\ProductImport\ProcessCategory;

class ProductImportParserActionsManager
{
    const FILE_JOB = "processYMLFile";
    const CATEGORY_JOB = "processCategory";
    const CATEGORY_PARENT_JOB = "processCategoryParent";

    const PRODUCT_JOB = "processProduct";
    const OFFER_JOB = "processOffer";

    const OTHER_CATEGORY_JOB = "processOtherCategory";
    const OTHER_PRODUCT_JOB = "processOtherProduct";

    protected null|\SimpleXMLElement $ymlParser;
    protected null|int $ymlFileId;
    protected $import;
    protected $importProducts;
    protected $offers;
    protected $props;

    public function __construct()
    {
        $this->ymlFileId = null;
        $this->ymlParser = null;
        $this->import = null;
        $this->importProducts = null;
        $this->offers = null;
        $this->props = [];
    }

    /**
     * Начать обработку файла.
     *
     * @param YmlFile $file
     * @return void
     */
    public function parseFile(YmlFile $file)
    {
        $path = Storage::disk("public")->path($file->path);

        $this->ymlFileId = $file->id;
        try {
            $this->ymlParser = simplexml_load_string(file_get_contents($path));
        }
        catch (\Exception $e){
            Log::warning("Невалидный YML: ".$e);
        }

        switch ($file->type) {
            case "catalog":  case "import": {
            $this->prepareImport();
            }

            case "offers":
                $this->prepareOffers();
                break;
        }
    }

    /**
     * Проверить обработку файла.
     *
     * @param ImportYml $yml
     * @return string
     */
    public function initParseFile(ImportYml $yml)
    {
        $original = ProductImportLoadFileActions::checkFileName();
        if (! $original) return ProductImportProtocolActions::failure("File name not found");

        $file = $this->getYmlFile($yml, $original);
        if (is_string($file)) return $file;

        return $this->checkProgress($file);
    }

    /**
     * Найти файл вугрузки.
     *
     * @param ImportYml $yml
     * @param $original
     * @return bool|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    protected function getYmlFile(ImportYml $yml, $original)
    {
        try {
            return $yml->files()
                ->where("original_name", $original)
                ->firstOrFail();
        }
        catch (\Exception $exception) {
            return ProductImportProtocolActions::failure("File not found");
        }
    }

    /**
     * Проверить статус обработки файла.
     *
     * @param YmlFile $file
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function checkProgress(YmlFile $file): string
    {
        if ($this->getJobsCount()) return ProductImportProtocolActions::answer("progress");

        if (empty($file->started_at)) {
            ProcessYmlFile::dispatch($file)->onQueue(self::FILE_JOB);
            $file->started_at = now();
            $file->save();

            return ProductImportProtocolActions::answer("progress");
        }
        else {
            if (base_config()->get("product-import", "xml-category-import-type") == "full"
            || base_config()->get("product-import", "xml-product-import-type") == "full")
            {
                if (empty($file->full_import_at)) {
                    if ($file->type !== "offers"){
                        if (base_config()->get("product-import", "xml-category-import-type") == "full")
                            $this::otherCategories($file->id);
                        if (base_config()->get("product-import", "xml-product-import-type") == "full")
                            $this::otherProducts($file->id);
                    }
                    $file->full_import_at = now();
                    $file->save();
                    return ProductImportProtocolActions::answer("progress");
                }
                else
                    return ProductImportProtocolActions::answer("success");
            }
            else{
                return ProductImportProtocolActions::answer("success");
            }
        }
    }

    /**
     * Посчитать элементы в очереди.
     *
     * @return int
     */
    public function getJobsCount()
    {
        return DB::table("jobs")
            ->select("id")
            ->whereIn("queue", $this->getJobsNames())
            ->count();
    }

    /**
     * Получить имена очередей: перечислить все очереди
     *
     * @return array
     */
    protected function getJobsNames()
    {
        return [
            self::FILE_JOB,
            self::CATEGORY_JOB,
            self::CATEGORY_PARENT_JOB,
            self::PRODUCT_JOB,
            self::OFFER_JOB,
            self::OTHER_CATEGORY_JOB,
            self::OTHER_PRODUCT_JOB,
        ];
    }

    /**
     * Обработка импорта
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function prepareImport()
    {
        if (empty($this->ymlParser->{base_config()->get("product-import","xml-root")}))
             return;

        $this->import = $this->ymlParser->{base_config()->get("product-import","xml-root")};

        if (! empty($this->ymlParser->{base_config()->get("product-import","xml-root-product")}))
            $this->importProducts = $this->ymlParser->{base_config()->get("product-import","xml-root-product")};
        else
            $this->importProducts = $this->import;

        $this->initUpdateCategories();
        $this->initUpdateProducts();
    }

    /**
     * Обработка предложений.
     */
    protected function prepareOffers()
    {
        if (empty($this->ymlParser->{base_config()->get("product-import","xml-variations-root")})) return;

        $this->offers = $this->ymlParser->{base_config()->get("product-import","xml-variations-root")};

        $this->initUpdateOffers();
    }

    /**
     * Рарабрать структуру цен.
     */
    protected function initUpdateOffers()
    {
        $importPath =  $this->offers->{base_config()->get("product-import","xml-variations")}[0];
        if (empty($importPath))
            return ProductImportProtocolActions::failure("Offer's structure not found");

        $groups = $importPath->children();

        $this->addOffersToQueue($groups);
    }
    /**
     * Проверить структуру товаров.
     *
     * @return bool|string|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function initUpdateProducts()
    {

        $importPath =  $this->importProducts->{base_config()->get("product-import","xml-products-root")}[0];
        if (empty($importPath))
            return ProductImportProtocolActions::failure("Product's structure not found");

        $this->getProps();
        $groups = $importPath->children();
        $this->addProductsToQueue($groups);
    }

    /**
     *  Добавить категории в очередь на обновление.
     *
     * @param \SimpleXMLElement $groups
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function addProductsToQueue(\SimpleXMLElement $groups)
    {
        foreach ($groups as $group) {
            ProcessProduct::dispatch($group->asXML(), $this->ymlFileId, $this->props)->onQueue(self::PRODUCT_JOB);
        }

    }

    /**
     * @param \SimpleXMLElement $groups
     * @return void
     */
    protected function addOffersToQueue(\SimpleXMLElement $groups)
    {
        foreach ($groups as $group) {
            ProcessOffer::dispatch($group->asXML())->onQueue(self::OFFER_JOB);
        }

    }

    /**
     * Получаем свойства товаров
     */
    protected function getProps()
    {
        $this->props = [];
        switch (base_config()->get("product-import","xml-prop-type")){
            case "list": case "list-element":
                if (empty($this->import->{base_config()->get("product-import","xml-prop-list-root")})) return;
                $loop = 0;
                foreach ($this->import->{base_config()->get("product-import","xml-prop-list-root")}[0]->children() as $item){
                    if (base_config()->get("product-import","xml-prop-type" == "list-element")){
                        $propId = ! empty($item) ?  str_replace(" ", "", $item->__toString()): null;
                        $propValue= ! empty($item) ? $item->__toString() : null;
                    }
                    else {
                        $propId= ! empty($item->{base_config()->get("product-import","xml-prop-list-id")}) ?
                            $item->{base_config()->get("product-import","xml-prop-list-id")}->__toString() : null;
                        $propValue= ! empty($item->{base_config()->get("product-import","xml-prop-list-name")}) ?
                            $item->{base_config()->get("product-import","xml-prop-list-name")}->__toString() : null;
                    }

                    if (empty($propValue)) continue;
                    //формируем массив свойств
                    $this->props[$propId] = $propValue;
                    $loop++;
                    //Log::info($propId." : ".$propValue);
                }
                break;

            default:
                break;
        }

    }

    /**
     * Проверить структуру категорий.
     *
     * @return bool|string|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function initUpdateCategories()
    {

        if ((base_config()->get("product-import","xml-categories-root-add") && base_config()->get("product-import","xml-categories-root-add") !== "")){
            $importPath = $this->import
                ->{base_config()->get("product-import","xml-categories-root")}[0]
                ->{base_config()->get("product-import","xml-categories-root-add")}[0];
        }
        else {
            $importPath = $this->import
                    ->{base_config()->get("product-import", "xml-categories-root")}[0];
        }
        if ($importPath)
            $groups = $importPath->children();
        else
            return ProductImportProtocolActions::failure("Structure not found");

        $this->addCategoriesToQueue(true,$groups);
    }

    /**
     *  Добавить категории в очередь на обновление.
     *
     * @param $isTree
     * @param \SimpleXMLElement $groups
     * @param $parent
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function addCategoriesToQueue($isTree, \SimpleXMLElement $groups, $parent = null)
    {
        $priority = 0;
        if (! $isTree) {
            foreach ($groups as $group){
                ProcessCategory::dispatch($group->asXML(), $parent, $priority++, $this->ymlFileId)->onQueue(self::CATEGORY_JOB);
            }
        }
        else {
            foreach ($groups as $group) {
                ProcessCategory::dispatch($group->asXML(), $parent, $priority++, $this->ymlFileId)->onQueue(self::CATEGORY_JOB);

                if (empty($group[0]->{base_config()->get("product-import","xml-categories-root")}[0]))
                    continue;
                if (base_config()->get("product-import", "xml-category-id-type") == "element"){
                    $current = ! empty($group[0]->{base_config()->get("product-import","xml-category-id")}) ?
                        $group[0]->{base_config()->get("product-import","xml-category-id")}->__toString()
                        : null;
                    $children = $group[0]
                        ->{base_config()->get("product-import","xml-categories-root")}[0]
                        ->children();
                    $this->addCategoriesToQueue($isTree, $children, $current);
                }
            }
        }
    }

    /**
     * Найти категорию по  внешнему uuid.
     *
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Category|null
     */
    public static function findCategoryByUUid(string $uuid)
    {
        try {
            return Category::query()
                ->where("import_uuid", $uuid)
                ->firstOrFail();
        }
        catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Найти Продукт по id импорта
     *
     * @param string $importId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public static function findProductByUUid(string $importId)
    {
        try {
            return Product::query()
                ->where("import_uuid", $importId)
                ->firstOrFail();
        }
        catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Скрыть непереданные категории
     * @param $ymlFileId
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function otherCategories($ymlFileId)
    {
        if(base_config()->get("product-import","xml-category-import-type") === "modify") return;
        $otherCategories = Category::query()
                ->where('yml_file_id', "!=", $ymlFileId)
                ->orWhereNull('yml_file_id')
                ->get();

            foreach ($otherCategories as $other){
                ProcessOtherCategory::dispatch($other)->onQueue(self::OTHER_CATEGORY_JOB);
            }
    }

    /**
     * Скрыть непереданные товары
     * @param $ymlFileId
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function otherProducts($ymlFileId)
    {
        if(base_config()->get("product-import","xml-product-import-type") === "modify") return;
        $otherProducts = Product::query()
            ->where('yml_file_id', "!=", $ymlFileId)
            ->orWhereNull('yml_file_id')
            ->get();

        foreach ($otherProducts as $other){
            ProcessOtherProduct::dispatch($other)->onQueue(self::OTHER_PRODUCT_JOB);
        }
    }

    /**
     * Возвращаем обработку скрытия отсутствующих категорий
     * @return float|int
     */
    public function getProgress(YmlFile $file)
    {
        return  $this->checkProgress($file);
    }

}