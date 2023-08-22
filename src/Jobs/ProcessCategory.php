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
use Notabenedev\ProductImport\Helpers\ProductImportParserActionsManager;

class ProcessCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected object $category;
    protected string $group;
    protected string|null $parent;
    protected int $priority;
    protected string $picture;

    /**
     * Create a new job instance.
     *
     * ProcessCategoryItem constructor.
     * @param $group
     * @param string|null $parent
     * @param int $priority
     */
    public function __construct( $group, $parent = null, $priority = 0)
    {
        $this->group = $group;
        $this->parent = $parent;
        $this->priority = $priority;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $category =  simplexml_load_string($this->group, \SimpleXMLElement::class,LIBXML_NOBLANKS | LIBXML_ERR_NONE);
        if (empty($category))   return;

        if (siteconf()->get("product-import", "xml-category-id-type") == "attribute" &&
            siteconf()->get("product-import", "xml-category-parent-type") == "attribute") {
            $id = null;
            $title = $category;
            foreach ($category->attributes() as $attribute => $value) {
                if ($attribute == siteconf()->get("product-import","xml-category-id")){
                    $id = $value;
                }
                if ($attribute == siteconf()->get("product-import","xml-category-parent-attribute")){
                    $this->parent = $value;
                }
            }
            $this->category = (object) [
                "id" => ! empty($id) ? $id->__toString() : null,
                "title" => ! empty($title) ? $title->__toString() : null,
                "parent" => ! empty($this->parent) ? $this->parent: null,
                "priority" => $this->priority,
            ];
        } elseif (siteconf()->get("product-import", "xml-category-parent-type") == "element-tree")
        {
            $id = $category[0]->{siteconf()->get("product-import","xml-category-id")};
            $title = $category[0]->{siteconf()->get("product-import","xml-category-element-tree-name")};
            $picture = $category[0]->{siteconf()->get("product-import","xml-category-element-tree-picture")};
            $this->category = (object) [
                "id" => ! empty($id) ? $id->__toString() : null,
                "title" => ! empty($title) ? $title->__toString() : null,
                "parent" => ! empty($this->parent) ? $this->parent: null,
                //"picture" => ! empty($this->picture) ? $this->picture: null,
                "priority" => $this->priority,
            ];
        }

        $category = $this->importItem();

        if (! empty($category)) {
            ProcessCategoryParent::dispatch($category)->onQueue(ProductImportParserActionsManager::CATEGORY_PARENT_JOB);
        }
    }

    /**
     * Обработать элемент.
     *
     * @return Category|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    protected function importItem()
    {
        if (empty($this->category->id) || empty($this->category->title)) return null;
        $model = ProductImportParserActions::findCategoryByUUid($this->category->id);

        if (empty($model)) {
            $model = Category::create(["title" => $this->category->title]);
            /**
             * @var Category $model
             */
            $model->priority = $this->category->priority;
            $model->import_uuid = $this->category->id;
            $model->import_parent = $this->category->parent;
            $model->save();
        }
        else {
            $model->title = $this->category->title;
            $model->priority = $this->category->priority;
            $model->import_parent = $this->category->parent;
            $model->save();
        }
        return $model;
    }
}
