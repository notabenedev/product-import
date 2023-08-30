<?php

namespace Notabenedev\ProductImport\Jobs;


use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;
use PortedCheese\CategoryProduct\Facades\ProductActions;


class ProcessProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected object $product;
    protected string $group;
    protected $props;
    protected int|null $ymlFileId;

    /**
     * Create a new job instance.
     *
     * ProcessCategoryItem constructor.
     * @param string $group
     * @param int|null $ymlFileId
     */
    public function __construct( string $group, $ymlFileId = null)
    {
        $this->group = $group;
        $this->ymlFileId = $ymlFileId;
    }

    /**
     * Execute the job
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle()
    {
        $product =  simplexml_load_string($this->group, \SimpleXMLElement::class,LIBXML_NOBLANKS | LIBXML_ERR_NONE);
        if (empty($product))   return;
        $id = null;
        $store = null;
        $code = null;
        if (siteconf()->get("product-import", "xml-product-id-type") == "attribute" ||
            siteconf()->get("product-import", "xml-code-type") == "product-attribute") {
            foreach ($product->attributes() as $attribute => $value) {
                switch ($attribute){
                    case siteconf()->get("product-import","xml-product-id"):
                        $id = $value;
                        break;
                    case siteconf()->get("product-import","xml-code"):
                        $code = $value;
                        break;
                }
            }
        }
        else{
            $id = $product[0]->{siteconf()->get("product-import","xml-category-id")};
        }

        if (siteconf()->get("product-import", "xml-code-type") == "product-element")
            $code = $product[0]->{siteconf()->get("product-import","xml-code")};

        $categoryId = $product[0]->{siteconf()->get("product-import","xml-product-category-id")};
        if (! empty(siteconf()->get("product-import", "xml-product-category-id-add")))
            $categoryId = $categoryId[0]->{siteconf()->get("product-import", "xml-product-category-id-add")};

        $title = $product[0]->{siteconf()->get("product-import","xml-product-name")};
        $description = $product[0]->{siteconf()->get("product-import","xml-product-description")};

        if (! siteconf()->get("product-import","xml-product-picture-add")){
            try {
                $picture = $product[0]
                        ->{siteconf()->get("product-import","xml-product-picture")};
                }
                catch (\Exception $e){
                    $picture = null;
                }
        }
        else
        {
            try {
                $picture = $product[0]
                    ->{siteconf()->get("product-import","xml-product-picture")}[0]
                    ->{siteconf()->get("product-import","xml-product-picture-add")};
            }
            catch (\Exception $e){
                $picture = null;
            }
        }

        $this->product = (object) [
            "id" => ! empty($id) ? $id->__toString() : null,
            "title" => ! empty($title) ? $title->__toString() : null,
            "categoryId" => ! empty($categoryId) ? $categoryId->__toString(): null,
            "picture" => ! empty($picture) ? $picture->__toString(): null,
            "ymlFileId" => $this->ymlFileId,
            "store" => ! empty($store) ? $store->__toString(): null,
            "code" => ! empty($code) ? $code->__toString(): null,
            "description" => ! empty($description) ? $description->__toString(): null,
        ];

        list($product,$category) = $this->importItem();
        if (empty($category))
            Log::warning("Категория для импорта не найдена. Товар $title не загружен.");
    }

    /**
     * Обработать элемент.
     *
     * @return array|null[]
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     */
    protected function importItem()
    {
        //ищем категорию, чтобы добавить в нее товар

        $category = ProductImportParserActions::findCategoryByUUid($this->product->categoryId);

        $isCategoryChanged = true;
        if (empty($this->product->id) || empty($category) || empty($this->product->title)) return [null, null];

        $model = ProductImportParserActions::findProductByUUid($this->product->id);

        if (empty($model)) {
            $model = $category->products()->create([
                "title" => $this->product->title,
                "description" => $this->product->description
            ]);
        }
        else {
            $model->title = $this->product->title;
            $model->description = $this->product->description;
            //изменилась ли категория товара
            $isCategoryChanged = $model->category_id !== $category->id;
        }

        $model->import_uuid = $this->product->id;
        $model->import_category = $this->product->categoryId;
        $model->import_code = $this->product->code;
        $model->yml_file_id = $this->ymlFileId;

        if(! empty($this->product->store) && $this->product->store == "false")
            $model->published_at = null;
        else
            $model->published_at = now();

        if($this->product->picture)
            if ( siteconf()->get("product-import","xml-picture-import-type") == "base64"){
                try{
                    $model->uploadBase64GalleryImage($this->product->picture, "gallery/products");
                }
                catch (\Exception $e){
                    Log::warning("Изображение base64 не загружено для Товара ".$this->product->title.":".$e);
                }
            }else {
                try {
                    $model->uploadUrlGalleryImage($this->product->picture, "gallery/products");
                }
                catch (\Exception $e){
                    Log::warning("Изображение url не загружено для Товара ".$this->product->title.":".$e);
                }
            }
        $model->save();

        if ($isCategoryChanged) ProductActions::changeCategory($model, $category->id);
        return [$model, $category];
    }
}
