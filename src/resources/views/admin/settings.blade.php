<div class="form-group">
    <h3>YML settings</h3>
</div>

<div class="form-group">
    <label class="text-secondary my-2" for="xmlRoot">Корневой xml-элемент</label>
    <input type="text"
           id="xmlRoot"
           name="data-xml-root"
           value="{{ old("xml-root", base_config()->get($name, "xml-root", "product-import")) }}"
           class="form-control @error("xml-root") is-invalid @enderror">
    @error("xml-root")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <div class="form-row">
        <div class="col-12 col-sm-6 col-lg-4">
            <h4>Категории</h4>

            <div class="form-group">
                <label class="text-secondary my-2" for="xmlCategoryImportType">Тип импорта категорий</label>
                <select type="text"
                        id="xmlCategoryImportType"
                        name="data-xml-category-import-type"
                        class="form-control @error("xml-category-import-type") is-invalid @enderror">
{{--                    <option value="full" {{ old("xml-category-import-type", base_config()->get($name, "xml-category-import-type", "modify")) == "full" ? " selected" : "" }}>--}}
{{--                        Полная выгрузка: !!! удаление всех отстутствующих категорий, товаров, цен, заказов !!!--}}
{{--                    </option>--}}
                    <option value="modify" {{ old("xml-category-id-type", base_config()->get($name, "xml-category-import-type", "modify")) == "modify" ? " selected" : "" }}>
                        Только изменения (изменение переданных категорий)
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label class="text-secondary my-2" for="xmlCategoriesRoot">Корневой xml элемент Категорий</label>
                <input type="text"
                       id="xmlCategoriesRoot"
                       name="data-xml-categories-root"
                       value="{{ old("xml-categories-root", base_config()->get($name, "xml-categories-root", "product-import")) }}"
                       class="form-control @error("xml-categories-root") is-invalid @enderror">
                @error("xml-categories-root")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror

                <label class="text-secondary my-2" for="xmlCategoriesRoot">Корневой xml элемент Категорий (Дополнительный)</label>
                <input type="text"
                       id="xmlCategoriesRootAdd"
                       name="data-xml-categories-root-add"
                       value="{{ old("xml-categories-root-add", base_config()->get($name, "xml-categories-root-add", "")) }}"
                       class="form-control @error("xml-categories-root-add") is-invalid @enderror">
                @error("xml-categories-root-add")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror

                <label class="text-secondary my-2" for="xmlCategory">xml элемент Категории</label>
                <input type="text"
                       id="xmlCategory"
                       name="data-xml-category"
                       value="{{ old("xml-category", base_config()->get($name, "xml-category", "product-import")) }}"
                       class="form-control @error("xml-category") is-invalid @enderror">
                @error("xml-category")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror

                <div class="form-row">
                    <div class="col-6">
                        <label class="text-secondary my-2" for="xmlCategoryIdType">Тип ID категории</label>
                        <select type="text"
                                id="xmlCategoryIdType"
                                name="data-xml-category-id-type"
                                class="form-control @error("xml-category-id-type") is-invalid @enderror">
                            <option value="element"
                                    {{ old("xml-category-id-type", base_config()->get($name, "xml-category-id-type", "product-import")) == "element" ? " selected" : "" }}>
                                Элемент
                            </option>
                            <option value="attribute"
                                    {{ old("xml-category-id-type", base_config()->get($name, "xml-category-id-type", "product-import")) == "attribute" ? " selected" : "" }}>
                                Атрибут
                            </option>
                        </select>
                        @error("xml-category-id-type")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="text-secondary my-2" for="xmlCategoryId">xml ID категории</label>
                        <input type="text"
                               id="xmlCategoryId"
                               name="data-xml-category-id"
                               value="{{ old("xml-category-id", base_config()->get($name, "xml-category-id", "product-import")) }}"
                               class="form-control @error("xml-category-id") is-invalid @enderror">
                        @error("xml-category-id")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="text-secondary my-2" for="xmlCategoryParentType">Структура категорий</label>
                <select type="text"
                        id="xmlCategoryParentType"
                        name="data-xml-category-parent-type"
                        class="form-control @error("xml-category-parent-type") is-invalid @enderror">
                    <option class="show-element-tree" value="element-tree"
                            {{ old("xml-category-parent-type", base_config()->get($name, "xml-category-parent-type", "product-import")) == "element-tree" ? " selected" : "" }}>
                        Дерево элементов
                    </option>
                    <option class="show-attribute" value="attribute"
                            {{ old("xml-category-parent-type", base_config()->get($name, "xml-category-parent-type", "product-import")) == "attribute" ? " selected" : "" }}>
                        Атрибут
                    </option>
                </select>
                @error("xml-category-parent-type")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror
                <div id="xmlCategoryParentAttributeBlock">
                    <label class="text-secondary my-2" for="xmlCategoryParentAttribute">Атрибут Родительской Категории</label>
                    <input type="text"
                           id="xmlCategoryParentAttribute"
                           name="data-xml-category-parent-attribute"
                           value="{{ old("xml-category-parent-attribute", base_config()->get($name, "xml-category-parent-attribute", "product-import")) }}"
                           class="form-control @error("xml-category-parent-attribute") is-invalid @enderror">
                    @error("xml-category-parent-attribute")
                    <div class="invalid-feedback" role="alert">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div id="xmlCategoryElementTreeBlock">
                    <div class="form-group">
                        <h5>Элементы Категории</h5>

                        <label class="text-secondary my-2" for="xmlCategoryElementTreeName">xml элемент Имя Категории</label>
                        <input type="text"
                               id="xmlCategoryElementTreeName"
                               name="data-xml-category-element-tree-name"
                               value="{{ old("xml-category-element-tree-name", base_config()->get($name, "xml-category-element-tree-name", "product-import")) }}"
                               class="form-control @error("xml-category-element-tree-name") is-invalid @enderror">
                        @error("xml-category-element-tree-name")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror

                        <label class="text-secondary my-2" for="xmlCategoryElementTreePicture">xml элемент Картинка Категории</label>
                        <input type="text"
                               id="xmlCategoryElementTreePicture"
                               name="data-xml-category-element-tree-picture"
                               value="{{ old("xml-category-element-tree-picture", base_config()->get($name, "xml-category-element-tree-picture", "product-import")) }}"
                               class="form-control @error("xml-category-element-tree-picture") is-invalid @enderror">
                        @error("xml-category-element-tree-picture")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <h4>Товары</h4>
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

                <label class="text-secondary my-2" for="xmlProductPicture">xml элемент Изображение Товара</label>
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
            </div>
        </div>
        <div class="col-12 col-lg-4">
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

                <label class="text-secondary my-2" for="xmlVariation">xml элемент Предложения</label>
                <input type="text"
                       id="xmlVariation"
                       name="data-xml-variation"
                       value="{{ old("xml-variation", base_config()->get($name, "xml-variation", "product-import")) }}"
                       class="form-control @error("xml-variation") is-invalid @enderror">
                @error("xml-variation")
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

                <label class="text-secondary my-2" for="xmlVariationPriceElement">xml элемент Описания Цены</label>
                <input type="text"
                       id="xmlVariationPriceElement"
                       name="data-xml-variation-price-element"
                       value="{{ old("xml-variation-price-element", base_config()->get($name, "xml-variation-price-element", "product-import")) }}"
                       class="form-control @error("xml-variation-price-element") is-invalid @enderror">
                @error("xml-variation-price-element")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror

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
            </div>
            <div class="form-group">
                <h3>Артикул</h3>
                <label class="text-secondary my-2" for="xmlCodeType">Расположение Артикула</label>
                <select type="text"
                        id="xmlCodeType"
                        name="data-xml-code-type"
                        class="form-control @error("xml-code-type") is-invalid @enderror">
                    <option value="product-element"
                            {{ old("xml-code-type", base_config()->get($name, "xml-code-type", "product-import")) == "product-element" ? " selected" : "" }}>
                        xml-элемент внутри xml-элемента Товара
                    </option>
                    <option value="product-attribute"
                            {{ old("xml-code-type", base_config()->get($name, "xml-code-type", "product-import")) == "product-attribute" ? " selected" : "" }}>
                        атрибут xml-элемента Товара
                    </option>
                    <option value="variation-element"
                            {{ old("xml-code-type", base_config()->get($name, "xml-code-type", "product-import")) == "variation-element" ? " selected" : "" }}>
                        xml-элемент внутри xml-элемента Предложения
                    </option>
                    <option value="etc"
                            {{ old("xml-code-type", base_config()->get($name, "xml-code-type", "product-import")) == "etc" ? " selected" : "" }}>
                        одинаковое значение для всех предложений
                    </option>
                </select>
                <label class="text-secondary my-2" for="xmlCode">xml Артикула (элемент/атрибут/etc)</label>
                <input type="text"
                       id="xmlCode"
                       name="data-xml-code"
                       value="{{ old("xml-code", base_config()->get($name, "xml-code", "product-import")) }}"
                       class="form-control @error("xml-code") is-invalid @enderror">
                @error("xml-code")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>
    </div>
</div>
