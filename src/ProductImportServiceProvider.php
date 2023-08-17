<?php

namespace Notabenedev\ProductImport;

use Illuminate\Support\ServiceProvider;
use Notabenedev\ProductImport\Console\Commands\ProductImportInitCommand;
use Notabenedev\ProductImport\Console\Commands\ProductImportMakeCommand;

class ProductImportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/product-import.php', 'product-import'
        );
        $this->initFacades();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Публикация конфигурации
        $this->publishes([
            __DIR__.'/config/product-import.php' => config_path('product-import.php')
        ], 'config');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProductImportMakeCommand::class,
                ProductImportInitCommand::class,
            ]);
        }

        // Подключение миграции
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'product-import');

        //Подключаем роуты
        if (config("product-import.importApiRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/product-import-api.php");
        }
        if (config("product-import.importAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/product-import-admin.php");
        }

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/scripts' => resource_path('js/vendor/product-import'),

        ], 'public');
    }

    /**
     * Подключение Facade.
     */
    protected function initFacades()
    {
        $this->app->singleton("product-import-auth-actions", function () {
            $class = config("product-import.productImportAuthActionsFacade");
            return new $class;
        });
        $this->app->singleton("product-import-protocol-actions", function () {
            $class = config("product-import.productImportProtocolActionsFacade");
            return new $class;
        });

        $this->app->singleton("product-import-load-file-actions", function () {
            $class = config("product-import.productImportLoadFileActionsFacade");
            return new $class;
        });
    }
}
