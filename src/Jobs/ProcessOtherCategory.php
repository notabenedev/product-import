<?php

namespace Notabenedev\ProductImport\Jobs;

use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PortedCheese\CategoryProduct\Facades\CategoryActions;


class ProcessOtherCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $other;

    /**
     * Create a new job instance.
     *
     * ProcessOtherCategory constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->other = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->other))
            return null;
        if ($this->other->published_at){
            $this->other->published_at = null;
            CategoryActions::runParentEvents($this->other);
            $this->other->save();
        }
    }

}
