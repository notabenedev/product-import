<?php

namespace Notabenedev\ProductImport\Helpers;

use App\ImportYml;
use App\YmlFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Notabenedev\ProductImport\Facades\ProductImportLoadFileActions;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;
use App\Jobs\Vendor\ProductImport\ProcessYmlFile;
use Zenwalker\CommerceML\CommerceML;

class ProductImportParserActionsManager
{
    const FILE_JOB = "processYMLFile";
    const CATEGORY_JOB = "processCategory";
    const CATEGORY_PARENT_JOB = "processCategoryParent";

    const PRODUCT_JOB = "processProduct";
    const OFFER_JOB = "processOffer";

    /**
     * @var null|CommerceML
     */
    protected $ymlParser;
    protected $import;
    protected $offers;
    protected $props;

    public function __construct()
    {
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
        $this->ymlParser = new CommerceML();
        $path = Storage::disk("public")->path($file->path);
        $this->ymlParser->loadImportXml($path);
        switch ($file->type) {
            case "catalog":
                //$this->prepareCatalog();
                break;
            case "import":
                //$this->prepareImport();
                break;

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
        ];
    }



}