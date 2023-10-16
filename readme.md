## Description
- импорт категорий, товаров и (если есть) цен из YML файла импорта

## Config
    php artisan vendor:publish --provider="Notabenedev\ProductImport\ProductImportServiceProvider" --tag=config --force

## Install
     -   php artisan vendor:publish --provider="Notabenedev\ProductImport\ProductImportServiceProvider" --tag=public --force
     -   php artisan make:product-import
                            {--all : Run all}
                            {--config : make config}
                            {--js : export js}
                            {--vue : export vue components}
                            {--models: export models}
                            {--policies: export rules}
                            {--only-default: create only default rules}
                            {--controllers: export controllers}
                            {--jobs: export jobs}
                            {--menu: create menu}
## Queue
    - php artisan queue:work --queue=default,processYmlFile,processCategory,processCategoryParent,processProduct,processOffer,processOtherCategory,processOtherProduct

## Update
   