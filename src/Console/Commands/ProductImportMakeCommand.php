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
                    {--vue : Export vue components}
                    {--models : Export models}
                    {--controllers : Export controllers}
                    {--policies : Export and create rules} 
                    {--only-default : Create only default rules}
                    {--jobs : Export jobs}
                    {--menu : create admin menu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make product-import settings';
    protected $vendorName = 'Notabenedev';
    protected $packageName = "ProductImport";

    protected $configName = "product-import";
    protected $configTitle = "Импорт Каталога";
    protected $configTemplate = "product-import::admin.settings";
    protected $configValues = [
        'xml-root' => 'yml_catalog',
        'xml-root-product' => '',

        'xml-picture-import-type' => 'base64',

        'xml-category-import-type' => 'modify',
        'xml-categories-root' => 'categories',
        'xml-categories-root-add' => '',
        'xml-category' => 'category',
        'xml-category-id-type' => 'attribute',
        'xml-category-id' => 'id',

        'xml-category-parent-type' => 'attribute',
        'xml-category-parent-attribute' => 'parentId',
        'xml-category-element-tree-name' => '',
        'xml-category-element-tree-picture' => '',
        'xml-category-element-tree-picture-add' => '',

        'xml-product-import-type' => 'modify',
        'xml-products-root' => 'offers',
        'xml-product' => 'offer',
        'xml-product-id-type' => 'attribute',
        'xml-product-id' => 'id',

        'xml-product-category-id' => 'categoryId',
        'xml-product-category-id-add' => '',

        'xml-product-name' => 'name',
        'xml-product-picture' => 'picture',
        'xml-product-picture-add' => '',
        'xml-product-description' => 'description',
        'xml-product-store' => '',

        'xml-variation-type' => 'product',
        'xml-product-price' => 'price',
        'xml-product-old-price' => 'oldprice',

        'xml-variations-root' => 'ПакетПредложений',
        'xml-variations' => 'Предложения',
        'xml-variation-product-id' => 'Ид',
        'xml-variation-product-title' => 'Наименование',
        'xml-variation-prices' => 'Цены',
        'xml-variation-price-element' => 'Цена',

        'xml-variation-price' => 'ЦенаСайта',
        'xml-variation-price-desc-type' => 'price',
        'xml-variation-price-desc' => 'Единица',
        'xml-variation-old-price' => 'ЦенаЗаЕдиницу',
        'xml-variation-count' => 'Количество',

        'xml-code-type' => 'product-attribute',
        'xml-code' => 'id',

        'xml-prop-type' => 'list',
        'xml-prop-list-root' => 'Свойства',
        'xml-prop-list-id' => 'Ид',
        'xml-prop-list-name' => 'Наименование',
        'xml-prop-group' => 'ЗначенияСвойств',
        'xml-prop' => 'ЗначенияСвойства',
        'xml-prop-id' => 'Ид',
        'xml-prop-value' => 'Значение',

    ];

    /**
     * The models to  be exported
     * @var array
     */
    protected $models = ["ImportYml", "YmlFile"];

    /**
     * Policies
     * @var array
     *
     */
    protected $ruleRules = [
        [
            "title" => "Импорт Каталога",
            "slug" => "product-import",
            "policy" => "ImportYmlPolicy",
        ],
    ];
    /**
     * Make Controllers
     */
    protected $controllers = [
        "Admin" => ["ImportYmlController"],
    ];

    /**
     * Make Jobs
     */

    protected $jobs = [
        "ProcessYmlFile",
        "ProcessCategory",
        "ProcessCategoryParent",
        "ProcessProduct",
        "ProcessOffer",
        "ProcessOtherCategory",
        "ProcessOtherProduct",
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
     * Папка для vue файлов.
     *
     * @var string
     */
    protected $vueFolder = "product-import";

    /**
     * Список vue файлов.
     *
     * @var array
     */
    protected $vueIncludes = [
        'admin' => [
            'progress-spinner' => "ProgressSpinnerComponent",
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

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }

        if ($this->option("controllers") || $all) {
             $this->exportControllers("Admin");
        }

        if ($this->option("jobs") || $all) {
            $this->exportJobs();
        }

        if ($this->option("js") || $all) {
            $this->makeJsIncludes("admin");
        }

        if ($this->option("vue") || $all) {
            $this->makeVueIncludes("admin");
        }

        if ($this->option("menu") || $all) {
            $this->makeMenu();
        }

    }

    protected function exportJobs()
    {
        if (empty($this->jobs)) {
            $this->info("jobs not found");
            return;
        }
        foreach ($this->jobs as $job) {
            if (file_exists(app_path("Jobs/Vendor/{$this->packageName}/{$job}.php"))) {
                if (! $this->confirm("The [$job.php] job already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            if (! is_dir($directory = app_path("Jobs/Vendor/{$this->packageName}"))) {
                mkdir($directory, 0755, true);
            }

            try {
                file_put_contents(
                    app_path("Jobs/Vendor/{$this->packageName}/{$job}.php"),
                    $this->compileJobStub($job)
                );

                $this->info("[$job.php] created");
            }
            catch (\Exception $e) {
                $this->error("Failed put job");
            }
        }
    }

    /**
     * Compile Job file
     *
     * @param $job
     * @return array|false|string|string[]
     */
    protected function compileJobStub($job)
    {
        return str_replace(
            ['{{vndName}}','{{namespace}}', '{{pkgName}}', "{{name}}"],
            [$this->vendorName, $this->getAppNamespace(), $this->packageName, $job],
            file_get_contents(__DIR__ . "/stubs/jobs/StubJob.stub")
        );
    }

    /**
     * Make menu
     *
     * @return void
     */
    protected function makeMenu()
    {
        try {
            $menu = Menu::query()
                ->where('key', 'admin')
                ->firstOrFail();
        }
        catch (\Exception $e) {
            return;
        }

        $title = "Импорт Каталога";

        $itemData = [
            'title' => $title,
            'template' => "product-import::admin.menu",
            'url' => "#",
            'ico' => 'fa-solid fa-file-import',
            'menu_id' => $menu->id,
        ];

        try {
            $menuItem = MenuItem::query()
                ->where("menu_id", $menu->id)
                ->where('title', $title)
                ->firstOrFail();
            $menuItem->update($itemData);
            $this->info("Элемент меню '$title' обновлен");
        }
        catch (\Exception $e) {
            MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
