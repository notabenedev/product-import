<h4>Цены</h4>
<label class="text-secondary my-2" for="xmlVariationType">Расположение цены</label>
<select type="text"
        id="xmlVariationType"
        name="data-xml-variation-type"
        class="form-control @error("xml-variation-type") is-invalid @enderror">
    <option class="show-product" value="product"
            {{ old("xml-variation-type", base_config()->get($name, "xml-variation-type", "product-import")) == "product" ? " selected" : "" }}>
        xml-элемент внутри xml-элемента Товара
    </option>
    <option class="show-file" value="file"
            {{ old("xml-variation-type", base_config()->get($name, "xml-variation-type", "product-import")) == "file" ? " selected" : "" }}>
        отдельный xml-файл
    </option>
</select>
<div class="form-group" id="xmlVariationProductBlock">
    <label class="text-secondary my-2" for="xmlProductPrice">xml элемент Цена Товара</label>
    <input type="text"
           id="xmlProductPrice"
           name="data-xml-product-price"
           value="{{ old("xml-product-price", base_config()->get($name, "xml-product-price", "")) }}"
           class="form-control @error("xml-product-price") is-invalid @enderror">
    @error("xml-product-price")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductOldPrice">xml элемент Старая цена Товара</label>
    <input type="text"
           id="xmlProductOldPrice"
           name="data-xml-product-old-price"
           value="{{ old("xml-product-old-price", base_config()->get($name, "xml-product-old-price", "")) }}"
           class="form-control @error("xml-product-old-price") is-invalid @enderror">
    @error("xml-product-old-price")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group" id="xmlVariationFileBlock">
    <label class="text-secondary my-2" for="xmlVariationsRoot">Корневой xml элемент Предложений</label>
    <input type="text"
           id="xmlVariationsRoot"
           name="data-xml-variations-root"
           value="{{ old("xml-variations-root", base_config()->get($name, "xml-variations-root", "product-import")) }}"
           class="form-control @error("xml-variations-root") is-invalid @enderror">
    @error("xml-variations-root")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariations">xml элемент Предложениий</label>
    <input type="text"
           id="xmlVariations"
           name="data-xml-variations"
           value="{{ old("xml-variations", base_config()->get($name, "xml-variations", "product-import")) }}"
           class="form-control @error("xml-variations") is-invalid @enderror">
    @error("xml-variations")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationProductId">xml элемент id Предложения</label>
    <input type="text"
           id="xmlVariationProductId"
           name="data-xml-variation-product-id"
           value="{{ old("xml-variation-product-id", base_config()->get($name, "xml-variation-product-id", "")) }}"
           class="form-control @error("xml-variation-product-id") is-invalid @enderror">
    @error("xml-variation-product-id")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationProductTitle">xml элемент заголовок Предложения</label>
    <input type="text"
           id="xmlVariationProductTitle"
           name="data-xml-variation-product-title"
           value="{{ old("xml-variation-product-title", base_config()->get($name, "xml-variation-product-title", "")) }}"
           class="form-control @error("xml-variation-product-title") is-invalid @enderror">
    @error("xml-variation-product-title")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationPrices">xml элемент Цен</label>
    <input type="text"
           id="xmlVariationPrices"
           name="data-xml-variation-prices"
           value="{{ old("xml-variation-prices", base_config()->get($name, "xml-variation-prices", "product-import")) }}"
           class="form-control @error("xml-variation-prices") is-invalid @enderror">
    @error("xml-variation-prices")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationPriceElement">xml элемент Цены</label>
    <input type="text"
           id="xmlVariationPriceElement"
           name="data-xml-variation-price-element"
           value="{{ old("xml-variation-price-element", base_config()->get($name, "xml-variation-price-element")) }}"
           class="form-control @error("xml-variation-price-element") is-invalid @enderror">
    @error("xml-variation-price-element")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <div class="form-group form-row">
        <div class="col-6">
            <label class="text-secondary my-2" for="xmlVariationPriceDescType">Тип Описания Цены</label>
            <select type="text"
                    id="xmlVariationPriceDescType"
                    name="data-xml-variation-price-desc-type"
                    class="form-control @error("xml-variation-price-desc-type") is-invalid @enderror">
                <option value="offer"
                        {{ old("xml-variation-price-desc-type", base_config()->get($name, "xml-variation-price-desc-type", "etc")) == "offer" ? " selected" : "" }}>
                    Значение xml-элемента Предложения
                </option>
                <option value="price"
                        {{ old("xml-variation-price-desc-type", base_config()->get($name, "xml-variation-price-desc-type", "etc")) == "price" ? " selected" : "" }}>
                    Значение xml-элемента Цены
                </option>
                <option value="etc"
                        {{ old("xml-variation-price-desc-type", base_config()->get($name, "xml-variation-price-desc-type", "etc")) == "etc" ? " selected" : "" }}>
                    Одинаковое значение для всех цен
                </option>
            </select>
        </div>
        <div class="col-6">
            <label class="text-secondary my-2" for="xmlVariationPriceDesc">xml элемент|etc Описания Цены</label>
            <input type="text"
                   id="xmlVariationPriceDesc"
                   name="data-xml-variation-price-desc"
                   value="{{ old("xml-variation-price-desc", base_config()->get($name, "xml-variation-price-desc", "")) }}"
                   class="form-control @error("xml-variation-price-desc") is-invalid @enderror">
            @error("xml-variation-price-desc")
            <div class="invalid-feedback" role="alert">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <label class="text-secondary my-2" for="xmlVariationPrice">xml элемент Цены в валюте</label>
    <input type="text"
           id="xmlVariationPrice"
           name="data-xml-variation-price"
           value="{{ old("xml-variation-price", base_config()->get($name, "xml-variation-price", "product-import")) }}"
           class="form-control @error("xml-variation-price") is-invalid @enderror">
    @error("xml-variation-price")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationOldPrice">xml элемент Старой Цены </label>
    <input type="text"
           id="xmlVariationOldPrice"
           name="data-xml-variation-old-price"
           value="{{ old("xml-variation-old-price", base_config()->get($name, "xml-variation-old-price", "")) }}"
           class="form-control @error("xml-variation-old-price") is-invalid @enderror">
    @error("xml-variation-old-price")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlVariationCount">xml элемент Количества </label>
    <input type="text"
           id="xmlVariationCount"
           name="data-xml-variation-count"
           value="{{ old("xml-variation-сount", base_config()->get($name, "xml-variation-count", "")) }}"
           class="form-control @error("xml-variation-count") is-invalid @enderror">
    @error("xml-variation-count")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror


</div>

