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

        $countStr = empty($offer[0]->{base_config()->get("product-import","xml-variation-count")}) ?
             null : $offer[0]->{base_config()->get("product-import","xml-variation-count")};
        $count =  ($countStr == null) ? null : intval($countStr);

        switch (base_config()->get("product-import","xml-code-type")){
            case "product-element": case "product-attribute": default:
                $code = null;
                break;
            case "variation-element":
                $code = ! empty($offer[0]->{base_config()->get("product-import","xml-code")}) ?
                    (int) $offer[0]->{base_config()->get("product-import","xml-code")} : null;
                break;
            case "etc":
                $code = ! empty($offer[0]->{base_config()->get("product-import","xml-code")}) ?
                    base_config()->get("product-import","xml-code") : null;
                break;
        }

        try {
            $offerPrices = $offer[0]->{base_config()->get("product-import","xml-variation-prices")}->children();
        }
        catch (\Exception $e){
            $offerPrices = [];
        }

        $desc = null;
        $price = null;
        $oldPrice = null;
        $sale = 0;

        foreach ($offerPrices as $offerPrice) {
            $desc = ! empty($offerPrice[0]->{base_config()->get("product-import","xml-variation-price-desc")}) ?
                $offerPrice[0]->{base_config()->get("product-import","xml-variation-price-desc")} : null;
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
            "id" => $id,
            "title" => $desc,
            "price" => $price,
            "oldPrice" => $oldPrice,
            "sale" => $sale,
            "disabled_at" => ($count == 0) ? now() : null,
            "code" => $code,
        ];

        if (empty($this->offer->id) || empty($this->offer->price))  return null;

        //ищем товар
        $product = ProductImportParserActions::findProductByUUid($this->offer->id);
        if (empty($product)) return null;

        //создать или обновить вариацию
        $this->createOrUpdateVariation($product);
    }

    /**
     * Создать или обновить вариацию для продукта
     *
     * @param Product $product
     * @return bool|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|ProductVariation|null
     */
    protected function createOrUpdateVariation(Product $product)
    {
        $variationData = $this->prepareVariationData($product);
        if (empty($variationData["price"])) return null;
        try {
            //получаем первую и единственную вариацию из модели
            $productVariation = $product->variations()->firstOrFail();

            $productVariation->update($variationData);
            return $productVariation;
        }
        catch(\Exception $exception)
        {
            //создаем вариацию для продукта
            return $product->variations()->create($variationData);
        }
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
        return [
            "description" => $this->offer->title,
            "disabled_at" => ! empty($this->offer->count) && $this->offer->count == 0 ? now() : null,
            "sku" => $code,
            "price" => $price,
            "sale_price" => $this->offer->oldPrice,
            "sale" => $this->offer->sale,
        ];
    }
}
