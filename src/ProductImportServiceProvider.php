<?php

namespace Notabenedev\ProductImport;

use Illuminate\Support\ServiceProvider;
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
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProductImportMakeCommand::class,
            ]);
        }
        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'product-import');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/scripts' => resource_path('js/vendor/product-import'),
        ], 'public');
    }
}
