<h4 class="mt-3">Товары</h4>
<div class="form-group">
    <label class="text-secondary my-2" for="xmlProductImportType">Тип импорта товаров</label>
    <select type="text"
            id="xmlProductImportType"
            name="data-xml-product-import-type"
            class="form-control @error("xml-product-import-type") is-invalid @enderror">
        <option value="full" {{ old("xml-product-import-type", base_config()->get($name, "xml-product-import-type", "modify")) == "full" ? " selected" : "" }}>
            Полная выгрузка: скрытие всех отстутствующих товаров
        </option>
        <option value="modify" {{ old("xml-product-import-type", base_config()->get($name, "xml-product-import-type", "modify")) == "modify" ? " selected" : "" }}>
            Только изменения (изменение переданных товаров)
        </option>
    </select>
</div>

<label class="text-secondary my-2" for="xmlRootProductSelect">Тип структуры импорта товаров</label>
<select type="text"
        id="xmlRootProductSelect"
        name="data-xml-root-product-select"
        class="form-control @error("xml-product-import-type") is-invalid @enderror">
    <option class="hide-root-product" value="root" {{ ! old("xml-product-import-type", base_config()->get($name, "xml-root-product", null)) ? " selected" : "" }}>
        В корневом xml-элементе
    </option>
    <option class="show-root-product" value="root-product" {{ old("xml-product-import-type", base_config()->get($name, "xml-root-product", null))  ? " selected" : "" }}>
        В отдельном xml-элементе товаров
    </option>
</select>

<div id="xmlRootProductBlock">
    <label class="text-secondary my-2" for="xmlRootProduct">Отдельный Корневой xml элемент Товаров</label>
    <input type="text"
           id="xmlRootProduct"
           name="data-xml-root-product"
           value="{{ old("xml-root-product", base_config()->get($name, "xml-root-product", null)) }}"
           class="form-control @error("xml-root-product") is-invalid @enderror">
    @error("xml-root-product")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>
<div id="xmlRootBlock"></div>

<label class="text-secondary my-2" for="xmlProductsRoot">Корневой xml элемент Товаров</label>
<input type="text"
       id="xmlProductsRoot"
       name="data-xml-products-root"
       value="{{ old("xml-products-root", base_config()->get($name, "xml-products-root", "product-import")) }}"
       class="form-control @error("xml-products-root") is-invalid @enderror">
@error("xml-products-root")
<div class="invalid-feedback" role="alert">
    {{ $message }}
</div>
@enderror

<label class="text-secondary my-2" for="xmlProductsRoot">xml элемент Товара</label>
<input type="text"
       id="xmlProduct"
       name="data-xml-product"
       value="{{ old("xml-product", base_config()->get($name, "xml-product", "product-import")) }}"
       class="form-control @error("xml-product") is-invalid @enderror">
@error("xml-product")
<div class="invalid-feedback" role="alert">
    {{ $message }}
</div>
@enderror

<div class="form-group form-row">
    <div class="col-6">
        <label class="text-secondary my-2" for="xmlProductIdType">Тип ID товара</label>
        <select type="text"
                id="xmlProductIdType"
                name="data-xml-product-id-type"
                class="form-control @error("xml-product-id-type") is-invalid @enderror">
            <option value="element"
                    {{ old("xml-product-id-type", base_config()->get($name, "xml-product-id-type", "product-import")) == "element" ? " selected" : "" }}>
                Элемент
            </option>
            <option value="attribute"
                    {{ old("xml-product-id-type", base_config()->get($name, "xml-product-id-type", "product-import")) == "attribute" ? " selected" : "" }}>
                Атрибут
            </option>
        </select>
        @error("xml-product-id-type")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="col-6">
        <label class="text-secondary my-2" for="xmlProductId">xml ID товара</label>
        <input type="text"
               id="xmlProductId"
               name="data-xml-product-id"
               value="{{ old("xml-product-id", base_config()->get($name, "xml-product-id", "product-import")) }}"
               class="form-control @error("xml-product-id") is-invalid @enderror">
        @error("xml-product-id")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror

    </div>
</div>

<div class="form-group">
    <label class="text-secondary my-2" for="xmlProductCategoryId">xml элемент Категория товара</label>
    <input type="text"
           id="xmlProductCategoryId"
           name="data-xml-product-category-id"
           value="{{ old("xml-product-category-id", base_config()->get($name, "xml-product-category-id", "product-import")) }}"
           class="form-control @error("xml-product-category-id") is-invalid @enderror">
    @error("xml-product-category-id")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductCategoryIdAdd">xml элемент Категория Товара (дополнительный)</label>
    <input type="text"
           id="xmlProductCategoryIdAdd"
           name="data-xml-product-category-id-add"
           value="{{ old("xml-product-category-id-add", base_config()->get($name, "xml-product-category-id-add", "")) }}"
           class="form-control @error("xml-product-category-id-add") is-invalid @enderror">
    @error("xml-product-category-id-add")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group">
    <label class="text-secondary my-2" for="xmlProductName">xml элемент Заголовок Товара</label>
    <input type="text"
           id="xmlProductName"
           name="data-xml-product-name"
           value="{{ old("xml-product-name", base_config()->get($name, "xml-product-name", "")) }}"
           class="form-control @error("xml-product-name") is-invalid @enderror">
    @error("xml-product-name")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductPicture">xml элемент Картинка Товара</label>
    <input type="text"
           id="xmlProductPicture"
           name="data-xml-product-picture"
           value="{{ old("xml-product-picture", base_config()->get($name, "xml-product-picture", "")) }}"
           class="form-control @error("xml-product-picture") is-invalid @enderror">
    @error("xml-product-picture")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductPictureAdd">xml элемент Картинка Товара (дополнительный)</label>
    <input type="text"
           id="xmlProductPictureAdd"
           name="data-xml-product-picture-add"
           value="{{ old("xml-product-picture-add", base_config()->get($name, "xml-product-picture-add", "")) }}"
           class="form-control @error("xml-product-picture-add") is-invalid @enderror">
    @error("xml-product-picture-add")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductDescription">xml элемент Описание Товара</label>
    <input type="text"
           id="xmlProductDescription"
           name="data-xml-product-description"
           value="{{ old("xml-product-description", base_config()->get($name, "xml-product-description", "")) }}"
           class="form-control @error("xml-product-description") is-invalid @enderror">
    @error("xml-product-description")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

    <label class="text-secondary my-2" for="xmlProductStore">xml элемент Доступность товара</label>
    <input type="text"
           id="xmlProductStore"
           name="data-xml-product-store"
           value="{{ old("xml-product-code", base_config()->get($name, "xml-product-store", "")) }}"
           class="form-control @error("xml-product-store") is-invalid @enderror">
    @error("xml-product-store")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>