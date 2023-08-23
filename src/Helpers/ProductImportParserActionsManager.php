<?php

namespace Notabenedev\ProductImport\Helpers;

use App\Category;
use App\ImportYml;
use App\Jobs\Vendor\ProductImport\ProcessCategoryParent;
use App\Jobs\Vendor\ProductImport\ProcessOtherCategory;
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

    protected null|\SimpleXMLElement $ymlParser;
    protected null|int $ymlFileId;
    protected $import;
    protected $offers;
    protected $props;

    public function __construct()
    {
        $this->ymlFileId = null;
        $this->ymlParser = null;
        $this->import = null;
        $this->offers = null;
        $this->props = [];
    }

    /**
     * Начать обработку файла.
     *
     * @param YmlFile $file
     */
    public function parseFile(YmlFile $file)
    {
        $path = Storage::disk("public")->path($file->path);

        $this->ymlFileId = $file->id;
        $this->ymlParser = simplexml_load_string(file_get_contents($path));

        switch ($file->type) {
            case "catalog":  case "import":
                $this->prepareImport();
                $this->otherCategories($file);

            case "offers":
                //$this->prepareOffers();
                break;
        }
    }

    /**
     * Проверить обработку файла.
     *
     * @param ImportYml $yml
     * @return YmlFile|string
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
     * @return YmlFile|string
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
            return ProductImportProtocolActions::answer("success");
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
            self::OTHER_CATEGORY_JOB
        ];
    }

    /**
     * Обработка импорта.
     */
    protected function prepareImport()
    {
        if (empty($this->ymlParser->{siteconf()->get("product-import","xml-root")}))
             return;
        else
            $this->import = $this->ymlParser->{siteconf()->get("product-import","xml-root")};

        $this->initUpdateCategories();
    }

    /**
     * Проверить структуру категорий.
     */
    protected function initUpdateCategories()
    {

        if ((siteconf()->get("product-import","xml-categories-root-add") && siteconf()->get("product-import","xml-categories-root-add") !== "")){
            $importPath = $this->import
                ->{siteconf()->get("product-import","xml-categories-root")}[0]
                ->{siteconf()->get("product-import","xml-categories-root-add")}[0];
        }
        else {
            $importPath = $this->import
                    ->{siteconf()->get("product-import", "xml-categories-root")}[0];
        }
        if ($importPath)
            $groups = $importPath->children();
        else
            return ProductImportProtocolActions::failure("Structure not found");

        $this->addCategoriesToQueue(true,$groups);
    }

    /**
     * Добавить категории в очередь на обновление.
     *
     * @param \SimpleXMLElement $groups
     * @param null $parent
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

                if (empty($group[0]->{siteconf()->get("product-import","xml-categories-root")}[0]))
                    continue;
                if (siteconf()->get("product-import", "xml-category-id-type") == "element"){
                    $current = ! empty($group[0]->{siteconf()->get("product-import","xml-category-id")}) ?
                        $group[0]->{siteconf()->get("product-import","xml-category-id")}->__toString()
                        : null;
                    $children = $group[0]
                        ->{siteconf()->get("product-import","xml-categories-root")}[0]
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
    public function findCategoryByUUid(string $uuid)
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
     * Обработка импорта.
     */
    protected function otherCategories(YmlFile $file)
    {
        if(siteconf()->get("product-import","xml-category-import-type") === "modify") return;

        $ymlStartedAt = $file->started_at;

        if (! empty($ymlStartedAt)) {

            $otherCategories = Category::query()
                ->where('yml_file_id', "<", $file->id)
                ->orWhereNull('yml_file_id')
                ->get();

            foreach ($otherCategories as $other){
                ProcessOtherCategory::dispatch($other)->onQueue(self::OTHER_CATEGORY_JOB);
            }
        }
    }
}