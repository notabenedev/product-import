<?php

namespace Notabenedev\ProductImport\Console\Commands;

use App\Menu;
use App\MenuItem;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class ProductImportMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:product-import
                    {--all : Run all}
                    {--config : Make config}    
                    {--js : Export scripts}
                    {--models : Export models}
                    {--controllers : Export controllers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make product-import settings';
    protected $vendorName = 'Notabenedev';
    protected $packageName = "ProductImport";

    protected $configName = "product-import";
    protected $configTitle = "Настройки импорта каталога";
    protected $configTemplate = "product-import::admin.settings";
    protected $configValues = [
        'xml-root' => 'yml_catalog',


        'xml-categories-root' => 'categories',
        'xml-category' => 'category',
        'xml-category-id-type' => 'attribute',
        'xml-category-id' => 'id',

        'xml-category-parent-type' => 'attribute',
        'xml-category-parent-attribute' => 'parentId',
        'xml-category-element-tree-name' => '',
        'xml-category-element-tree-picture' => '',

        'xml-products-root' => 'offers',
        'xml-product' => 'offer',
        'xml-product-id-type' => 'attribute',
        'xml-product-id' => 'id',

        'xml-product-category-id' => 'categoryId',
        'xml-product-category-id-add' => '',

        'xml-product-name' => 'name',
        'xml-product-picture' => 'picture',
        'xml-product-description' => 'description',

        'xml-variation-type' => 'product',
        'xml-product-price' => 'price',
        'xml-product-old-price' => 'oldprice',

        'xml-variations-root' => 'Предложения',
        'xml-variation' => 'Предложениe',
        'xml-variation-prices' => 'Цены',
        'xml-variation-price-element' => 'Цена',
        'xml-variation-price' => 'ЦенаЗаЕдиницу',

        'xml-code-type' => 'product-attribute',
        'xml-code' => 'id',

    ];

    /**
     * The models to  be exported
     * @var array
     */
    protected $models = ["ImportYml", "YmlFile"];

    /**
     * Make Controllers
     */
    protected $controllers = [
        "Admin" => ["ImportYmlController"],
    ];

    /**
     * Scripts.
     *
     * @var array
     */
    protected $jsIncludes = [
        "admin" => [
            "product-import/admin-product-import",
        ],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option("all");


        if ($this->option("config") || $all) {
            $this->makeConfig();
        }

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("controllers") || $all) {
             $this->exportControllers("Admin");
        }

        if ($this->option("js") || $all) {
            $this->makeJsIncludes("admin");
        }

    }
}
