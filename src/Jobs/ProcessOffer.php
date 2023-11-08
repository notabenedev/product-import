<?php

namespace Notabenedev\ProductImport\Jobs;


use App\Product;
use App\ProductVariation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Notabenedev\ProductImport\Facades\ProductImportParserActions;

class ProcessOffer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $group;
    protected object $offer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $group)
    {
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $offer = simplexml_load_string($this->group, \SimpleXMLElement::class,LIBXML_NOBLANKS | LIBXML_ERR_NONE);
        if (empty($offer))   return;

        $id = ! empty($offer[0]->{base_config()->get("product-import","xml-variation-product-id")}) ?
            $offer[0]->{base_config()->get("product-import","xml-variation-product-id")} : null;

        $ids = explode("#", $id);
        $prodId = $ids[0];
        $offerId = ! empty($ids[1]) ? $ids[1] : null;

        $countStr = empty($offer[0]->{base_config()->get("product-import","xml-variation-count")}) ?
             null : $offer[0]->{base_config()->get("product-import","xml-variation-count")};
        $count =  ! isset($countStr) ? null : intval($countStr);

        switch (base_config()->get("product-import","xml-code-type")){
            case "product-element": case "product-attribute": default:
                $code = null;
                break;
            case "variation-element":
                $code = ! empty($offer[0]->{base_config()->get("product-import","xml-code")}) ?
                    (int) $offer[0]->{base_config()->get("product-import","xml-code")} : null;
                break;
            case "etc":
                $code = ! empty(base_config()->get("product-import","xml-code")) ?
                    base_config()->get("product-import","xml-code") : null;
                break;
        }
        switch (base_config()->get("product-import","xml-variation-price-desc-type")){
            case "offer":
                $desc = ! empty($offer[0]->{base_config()->get("product-import","xml-variation-price-desc")}) ?
                     $offer[0]->{base_config()->get("product-import","xml-variation-price-desc")} : null;
                break;
            case "etc": default:
                $desc = ! empty(base_config()->get("product-import","xml-variation-price-desc")) ?
                    base_config()->get("product-import","xml-variation-price-desc") : null;
                break;
            case "price":
                $desc = "price";
        }

        try {
            $offerPrices = $offer[0]->{base_config()->get("product-import","xml-variation-prices")}->children();
        }
        catch (\Exception $e){
            $offerPrices = [];
        }

        //$desc = null;
        $price = null;
        $oldPrice = null;
        $sale = 0;

        foreach ($offerPrices as $offerPrice) {
            if ($desc == "price"){
                $desc = ! empty($offerPrice[0]->{base_config()->get("product-import","xml-variation-price-desc")}) ?
                    $offerPrice[0]->{base_config()->get("product-import","xml-variation-price-desc")} : null;
            }
            $price = ! empty($offerPrice[0]->{base_config()->get("product-import","xml-variation-price")}) ?
                $offerPrice[0]->{base_config()->get("product-import","xml-variation-price")} : null;
            $oldPrice = ! empty($offerPrice[0]->{base_config()->get("product-import","xml-variation-old-price")}) ?
                $offerPrice[0]->{base_config()->get("product-import","xml-variation-old-price")} : null;
            if ($oldPrice && $price && floatval($oldPrice) > floatval($price))
                $sale = 1;
            if ($price)
                break;
        }

        $this->offer = (object) [
            "prodId" => $prodId,
            "offerId" => ! empty($offerId) ?  $offerId  : null,
            "title" => $desc,
            "price" => $price,
            "oldPrice" => $oldPrice,
            "sale" => $sale,
            "count" => $count,
            "code" => $code,
        ];
        if (empty($this->offer->prodId) || empty($this->offer->price))  return null;
        //ищем товар
        $product = ProductImportParserActions::findProductByUUid($this->offer->prodId);
        if (empty($product)) return null;

        //создать или обновить вариацию
        $this->createOrUpdateVariation($product, $offerId);
    }

    /**
     * Создать или обновить вариацию для продукта
     *
     * @param Product $product
     * @return bool|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|ProductVariation|null
     */
    protected function createOrUpdateVariation(Product $product, $offerId = null)
    {
        $variationData = $this->prepareVariationData($product);
        if (empty($variationData["price"])) return null;
        try {
            //получаем  единственную вариацию из модели
            $productVariation = $product->variations();
            if (! empty($offerId))
                $productVariation =  $productVariation->where("import_uuid","=",$offerId);
            $productVariation = $productVariation->firstOrFail();
            $productVariation->update($variationData);
        }
        catch(\Exception $exception)
        {
            //создаем вариацию для продукта
            $productVariation = $product->variations()->create($variationData);
        }

        return $productVariation;
    }

    /**
     * Подготовка данных для вариации продукта
     *
     * @param Product $product
     * @return array
     */
    protected function prepareVariationData(Product $product)
    {
        $price = ! empty($this->offer->price) ? $this->offer->price : $this->offer->oldPrice;
        $code = !empty($this->offer->code) ? $this->offer->code : $product->import_code;
        $disabled_at = (isset($this->offer->count) && $this->offer->count == 0) ? now() : null;
        return [
            "description" => $this->offer->title,
            "disabled_at" => $disabled_at,
            "sku" => $code,
            "price" => $price,
            "sale_price" => $this->offer->oldPrice,
            "sale" => $this->offer->sale,
            "import_uuid" => $this->offer->offerId,
        ];
    }
}
