<?php

namespace Notabenedev\ProductImport\Jobs;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PortedCheese\CategoryProduct\Events\CategorySpecificationValuesUpdate;
use PortedCheese\CategoryProduct\Events\ProductListChange;
use PortedCheese\CategoryProduct\Facades\CategoryActions;


class ProcessOtherProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $other;

    /**
     * Create a new job instance.
     *
     * ProcessOtherCategory constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->other = $product;
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
            $category = $this->other->category;
            // При отключении товара меняется набор характеристик для фильтрации.
            event(new CategorySpecificationValuesUpdate($category));
            // Вызвать событие изменения списка товаров.
            event(new ProductListChange($category));
            CategoryActions::runParentEvents($category);
            $this->other->save();
        }
    }

}
