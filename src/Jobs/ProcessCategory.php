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
    protected int|null $ymlFileId;

    /**
     * Create a new job instance.
     *
     * ProcessCategoryItem constructor.
     * @param $group
     * @param string|null $parent
     * @param int $priority
     * @param int|null $ymlFileId
     */
    public function __construct( $group, $parent = null, $priority = 0,  $ymlFileId = null)
    {
        $this->group = $group;
        $this->parent = $parent;
        $this->priority = $priority;
        $this->ymlFileId = $ymlFileId;
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
                "ymlFileId" => $this->ymlFileId,
                "picture" => null,
            ];
        } elseif (siteconf()->get("product-import", "xml-category-parent-type") == "element-tree")
        {
            $id = $category[0]->{siteconf()->get("product-import","xml-category-id")};
            $title = $category[0]->{siteconf()->get("product-import","xml-category-element-tree-name")};
            if (! siteconf()->get("product-import","xml-category-element-tree-picture-add")){
                try {
                    $picture = $category[0]
                        ->{siteconf()->get("product-import","xml-category-element-tree-picture")};
                }
                catch (\Exception $e){
                    $picture = null;
                }
            }
            else
                try {
                    $picture = $category[0]
                        ->{siteconf()->get("product-import","xml-category-element-tree-picture")}[0]
                        ->{siteconf()->get("product-import","xml-category-element-tree-picture-add")};
                }
                catch (\Exception $e){
                    $picture = null;
                }

            $this->category = (object) [
                "id" => ! empty($id) ? $id->__toString() : null,
                "title" => ! empty($title) ? $title->__toString() : null,
                "parent" => ! empty($this->parent) ? $this->parent: null,
                "picture" => ! empty($picture) ? $picture->__toString(): null,
                "priority" => $this->priority,
                "ymlFileId" => $this->ymlFileId,
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
        }
        else {
            $model->title = $this->category->title;
        }

        $model->import_uuid = $this->category->id;
        $model->import_parent = $this->category->parent;
        $model->yml_file_id = $this->ymlFileId;
        $model->published_at = now();
        if($this->category->picture)
            if ( siteconf()->get("product-import","xml-picture-import-type") == "base64"){
                try{
                    $model->uploadBase64Image($this->category->picture, "categories");
                }
                catch (\Exception $e){
                    Log::warning("Изображение base64 не загружено для Категории ".$this->category->title.":".$e);
                }

            }else {
                try{
                    $model->uploadUrlImage($this->category->picture, "categories");
                }
                catch (\Exception $e){
                    Log::warning("Изображение url не загружено для Категории ".$this->category->title.":".$e);
                }
            }
        $model->save();
        return $model;
    }
}
