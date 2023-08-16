<?php

namespace Notabenedev\ProductImport\Helpers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ProductImportProtocolActionsManager
{
    protected $yml = 'import.xml';
    protected $path = 'product-import/';

    public function __construct(){

    }

    public function init(){
        if (Storage::exists($this->path.$this->yml)){
            Log::info("Exists");
        }
        else{
            Log::info("No file");
        }
    }

}