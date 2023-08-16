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
    protected $signature = 'init:product-import';

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
       ProductImportProtocolActions::init();
       return 0;
    }
}
