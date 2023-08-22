<?php

namespace Notabenedev\ProductImport\Jobs;

use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;

class ProcessCategoryParent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;

    /**
     * Create a new job instance.
     *
     * @param Category $category
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oldParent = $this->category->parent;
        $newCategoryId = $this->category->import_parent;

        if (! empty($oldParent) && $oldParent->import_uuid == $newCategoryId) {
            return;
        }
        if (! empty($oldParent) && empty($newCategoryId)) {
            $this->category->parent()->dissociate();
            $this->category->save();
            return;
        }
        elseif (empty($newCategoryId)) return;

        $parent = ProductImportParserActions::findCategoryByUUid($newCategoryId);
        if (empty($parent)) {
            $this->category->parent()->dissociate();
            $this->category->save();
            return;
        }

        $this->category->parent()->associate($parent);
        $this->category->save();
    }
}
