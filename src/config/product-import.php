<?php
return [

    "importApiRoutes" => true,
    "importAdminRoutes" => true,
    "productImportProtocolActionsFacade" => \Notabenedev\ProductImport\Helpers\ProductImportProtocolActionsManager::class,
    "productImportAuthActionsFacade" => \Notabenedev\ProductImport\Helpers\ProductImportAuthActionsManager::class,
    "productImportLoadFileActionsFacade" => \Notabenedev\ProductImport\Helpers\ProductImportLoadFileActionsManager::class,
    "productImportParserActionsFacade" => \Notabenedev\ProductImport\Helpers\ProductImportParserActionsManager::class,
    "productImportImageActionsFacade" => \Notabenedev\ProductImport\Helpers\ProductImportImageActionsManager::class,

];