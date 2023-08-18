<?php

namespace Notabenedev\ProductImport\Jobs;

use App\YmlFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;

class ProcessYmlFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     *
     * ProcessYmlFile constructor.
     * @param YmlFile $file
     */
    public function __construct(YmlFile $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ProductImportParserActions::parseFile($this->file);
    }
}
