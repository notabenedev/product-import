<?php

namespace Notabenedev\ProductImport\Console\Commands;

use Illuminate\Console\Command;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

class ProductImportInitCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:product-import
    {--form-mode : form-mode import}
    {--console-mode : console-mode import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init product-import protocol';

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
        $mode = "init";
        if ($this->option("form-mode"))
            $mode = "form";
        if ($this->option("console-mode"))
            $mode = "console";
       ProductImportProtocolActions::init($mode);
       return 0;
    }
}
