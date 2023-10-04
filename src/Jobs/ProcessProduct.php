<?php

namespace Notabenedev\ProductImport\Jobs;


use App\Category;
use App\Product;
use App\ProductSpecification;
use App\ProductVariation;
use App\Specification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;
use PortedCheese\CategoryProduct\Events\CategorySpecificationUpdate;
use PortedCheese\CategoryProduct\Facades\ProductActions;


class ProcessProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected object $product;
    protected string $group;
    protected $price;
    protected array $props;
    protected int|null $ymlFileId;

    /**
     * Create a new job instance.
     *
     * ProcessCategoryItem constructor.
     * @param string $group
     * @param int|null $ymlFileId
     */
    public function __construct( string $group, $ymlFileId = null, array $props = [])
    {
        $this->group = $group;
        $this->ymlFileId = $ymlFileId;
        $this->props = $props;
        $this->price = null;
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
        if (base_config()->get("product-import", "xml-product-id-type") == "attribute" ||
            base_config()->get("product-import", "xml-code-type") == "product-attribute") {
            foreach ($product->attributes() as $attribute => $value) {
                switch ($attribute){
                    case base_config()->get("product-import","xml-product-id"):
                        $id = $value;
                        break;
                    case base_config()->get("product-import","xml-code"):
                        $code = $value;
                        break;
                }
            }
        }
        else{
            $id = $product[0]->{base_config()->get("product-import","xml-category-id")};
        }

        if (base_config()->get("product-import", "xml-code-type") == "product-element")
            $code = $product[0]->{base_config()->get("product-import","xml-code")};

        $categoryId = $product[0]->{base_config()->get("product-import","xml-product-category-id")};
        if (! empty(base_config()->get("product-import", "xml-product-category-id-add")))
            $categoryId = $categoryId[0]->{base_config()->get("product-import", "xml-product-category-id-add")};

        $title = $product[0]->{base_config()->get("product-import","xml-product-name")};
        $description = $product[0]->{base_config()->get("product-import","xml-product-description")};

        if (! base_config()->get("product-import","xml-product-picture-add")){
            try {
                $picture = $product[0]
                        ->{base_config()->get("product-import","xml-product-picture")};
                }
                catch (\Exception $e){
                    $picture = null;
                }
        }
        else
        {
            try {
                $picture = $product[0]
                    ->{base_config()->get("product-import","xml-product-picture")}[0]
                    ->{base_config()->get("product-import","xml-product-picture-add")};
            }
            catch (\Exception $e){
                $picture = null;
            }
        }

        $productProps = [];
        switch (base_config()->get("product-import","xml-prop-type")){
            case "list":
                if (!empty($product[0]->{base_config()->get("product-import","xml-prop-group")})) {
                    foreach ($product[0]->{base_config()->get("product-import","xml-prop-group")}->children() as $prop) {
                        $propId = ! empty($prop->{base_config()->get("product-import","xml-prop-id")}) ?
                            $prop->{base_config()->get("product-import","xml-prop-id")}->__toString() : null;
                        if (empty($propId)) continue;

                        $propValue = ! empty($prop->{base_config()->get("product-import","xml-prop-value")}) ?
                            trim(mb_strtolower($prop->{base_config()->get("product-import","xml-prop-value")}->__toString())) : null;
                        if (empty($propValue)) continue;
                        $productProps[$propId] = $propValue;
                        //Log::info($propId."-".$propValue);
                    }
                }
                break;
            case "list-element":
                foreach ($this->props as $propListElement => $propListName){
                    if (!empty($product[0]->{$propListElement})) {
                        $prop = $product[0]->{$propListElement};
                        $propId = $propListElement;
                        if (empty($propId)) continue;
                        $propValue = trim(mb_strtolower($prop->__toString()));
                        if (empty($propValue)) continue;
                        $productProps[$propId] = $propValue;
                        //Log::info($propId."-".$propValue);
                    }
                }
                break;
            case "param":
                if (!empty($product[0]->{base_config()->get("product-import","xml-prop")})) {
                    foreach ($product[0]->{base_config()->get("product-import","xml-prop")} as $prop) {
                        foreach ($prop->attributes() as $attribute => $value){
                            if ($attribute == base_config()->get("product-import","xml-prop-value")){
                                $propId = $value->__toString();
                                $propValue = trim(mb_strtolower($prop[0]->__toString()));
                                if (empty($propId)) continue;
                                if (empty($propValue)) continue;
                                $productProps[$propId] = $propValue;
                                //Log::info($propId."-".$propValue);
                            }
                        }

                    }
                }
                break;
        }

        if (base_config()->get("product-import","xml-variation-type") == "product"){
            $price["price"] = isset($product[0]->{base_config()->get("product-import","xml-product-price")}) ?
                $product[0]->{base_config()->get("product-import","xml-product-price")} : null;
            $price["oldPrice"] = isset($product[0]->{base_config()->get("product-import","xml-product-old-price")}) ?
                $product[0]->{base_config()->get("product-import","xml-product-old-price")} : null;
        }
        else
        {
            $price = null;
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
            "props" => $productProps,
            "price" => $price,
        ];

        list($product,$category) = $this->importItem();

        if (empty($category))
            Log::warning("Категория для импорта не найдена. Товар $title не загружен.");

        if (! empty($product)) {
            $this->importSpecifications($product, $category);
            $this->importVariations($product);
        }
    }

    /**
     * @param Product $product
     * @return void
     */
    protected function importVariations(Product $product){
        if (base_config()->get("product-import","xml-variation-type") == "product") {
            $forDelete = $product->variations()->get();
            foreach ($forDelete as $item) {
                $item->delete();
            }
            $this->createProductVariation($product);
        }
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
            if ( base_config()->get("product-import","xml-picture-import-type") == "base64"){
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

    /**
     *
     * @param Product $product
     * @param Category $category
     */
    protected function importSpecifications(Product $product, Category $category)
    {
        if (empty($this->product->props)) {
            // удаляем все спецификации товара
            $forDelete = $product->specifications()
                ->get();
        }
        else {
            if (base_config()->get("product-import","xml-prop-type") !== "list") {
                $forDelete = $product->specifications()
                    ->get();
                $changedSpecsIds = $this->createOrUpdateProductProps($product, $category);
            }
            else {
                // удаляем недобавленные спецификации
                $changedSpecsIds = $this->createOrUpdateProductProps($product, $category);
               $forDelete = $product->specifications()->whereNotIn("id", $changedSpecsIds)->get();
            }
        }


        foreach ($forDelete as $item) {
            $item->delete();
        }
    }

    /**
     * @param Product $product
     * @param Category $category
     * @return array
     */
    protected function createOrUpdateProductProps(Product $product, Category $category): array
    {
        $productSpecs = [];
        foreach ($this->product->props as $id => $value) {
            // обновляем или создаем характиристику в таблице Specifications и в Категориях
            $specification = $this->createOrUpdateSpecifications($id);

            if (empty( $specification)) continue;
            $this->attachCategorySpecification($category, $specification);
            // check and  update or create specification
            $productSpecification = $this->setProductSpecValue($product, $category, $specification, $value);
            //Log::info($productSpecification);
            $productSpecs[] = $productSpecification->id;
        }
        return $productSpecs;

    }

    /**
     * Проверяем, а затем обновляем или создаем спецификацию товара
     *
     * @param Product $product
     * @param Category $category
     * @param Specification $specification
     * @param string $propValue
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|ProductSpecification
     */
    protected function setProductSpecValue(Product $product, Category $category, Specification $specification, string $propValue)
    {
        try {
            $value = $product->specifications()
                ->where("specification_id", $specification->id)
                ->firstOrFail();
            if ($value->value !== $propValue) {
                //обновляем в модели поле Value
                $value->update(["value" => $propValue]);
            }
        } catch (\Exception $exception) {
            $value = $product->specifications()->create([
                "value" => $propValue,
                "specification_id" => $specification->id,
                "category_id" => $category->id,
            ]);
        }
        return $value;
    }

    /**
     * Добавить характеристику в категорию.
     *
     * @param Category $category
     * @param Specification $specification
     */
    protected function attachCategorySpecification(Category $category, Specification $specification)
    {
        try {
            $category->specifications()
                ->where("id", $specification->id)
                ->firstOrFail();
        } catch (\Exception $exception) {
            $specification->categories()->attach($category, [
                "title" => $specification->title,
                "filter" => 1,
            ]);
            event(new CategorySpecificationUpdate($category));
        }
    }

    /**
     * Найти характеристику.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Specification|null
     */
    protected function createOrUpdateSpecifications(string $id)
    {
        switch (base_config()->get("product-import","xml-prop-type")){
            case "list":
                if (empty($this->props[$id]))
                    return null;
                $name = $this->props[$id];
                $field = "import_uuid";
                $search = $id;
                break;
            case "list-element":
                if (empty($this->props[$id]))
                    return null;
                $name = $this->props[$id];
                $field = "title";
                $search = $name;
                break;
            case "param":
                $name = $id;
                $field = "title";
                $search = $name;
                break;
        }

        //ищем свойство в таблице Cпецификации
        try {
            $specification = Specification::query()
                ->where($field, $search)
                ->firstOrFail();
            if ($specification->title !== $name) {
                $specification->title = $name;
                $specification->save();
                //обновление спецификаций в категориях.
                foreach ($specification->categories as $category) {
                    /**
                     * @var Category $category
                     */
                    $category->specifications()
                        ->updateExistingPivot($specification->id, [
                            "title" => $name,
                        ]);
                    event(new CategorySpecificationUpdate($category));
                }
            }
        } catch (\Exception $exception) {
            $specification = Specification::create([
                "title" => $name,
                "type" => "checkbox"
            ]);
            $specification->import_uuid = $id;
            $specification->save();
        }

        return $specification;
    }

    /**
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function createProductVariation(Product $product)
    {
        $price = ! empty($this->product->price["price"]) ? $this->product->price["price"] : null;
        $oldPrice = ! empty($this->product->price["oldPrice"]) ? $this->product->price["oldPrice"] : null;
        $sale = (!empty($oldPrice) && (floatval($oldPrice) > floatval($price))) ? 1 : 0;
        if (! $price) return null;
        try {
            $productVariation = $product->variations()->create([
                "price" => $price,
                "sale_price" => $oldPrice,
                "sale" => $sale,
            ]);
            $productVariation->save();
            return  $productVariation;
        } catch (\Exception $exception) {
               return null;
        }
    }
}
