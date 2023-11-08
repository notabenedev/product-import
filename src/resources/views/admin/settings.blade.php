<div class="form-group">
    <h3>
        <a href="#" onclick="cmlSettings()" title="Сгенерировать настройки для CML">CML</a> |
        <a href="#" onclick="ymlSettings()" title="Сгенерировать настройки для YML">YML</a>  settings
    </h3>
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
    <label class="text-secondary my-2" for="xmlPictureImportType">Тип изображений</label>
    <select type="text"
            id="xmlPictureImportType"
            name="data-xml-picture-import-type"
            class="form-control @error("xml-picture-import-type") is-invalid @enderror">
        <option value="base64" {{ old("xml-picture-import-type", base_config()->get($name, "xml-picture-import-type", "base64")) == "base64" ? " selected" : "" }}>
            Кодировка base64
        </option>
        <option value="href" {{ old("xml-picture-import-type", base_config()->get($name, "xml-picture-import-type", "href")) == "href" ? " selected" : "" }}>
            Гиперссылка на изображение
        </option>
    </select>
</div>

<div class="form-group">
    <div class="form-row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#categories" type="button" role="tab"
                            aria-controls="categories"
                            aria-selected="true">Категории</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="products-tab" data-toggle="tab" data-target="#products" type="button" role="tab"
                            aria-controls="products" aria-selected="false">Товары</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="prices-tab" data-toggle="tab" data-target="#prices" type="button" role="tab"
                            aria-controls="prices" aria-selected="false">Цены</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-toggle="tab" data-target="#specifications" type="button" role="tab"
                            aria-controls="specifications" aria-selected="false">Свойства</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    @include("product-import::admin.includes.categories")
                </div>
                <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                    @include("product-import::admin.includes.products")
                </div>
                <div class="tab-pane fade" id="prices" role="tabpanel" aria-labelledby="prices-tab">
                    @include("product-import::admin.includes.variations")
                    @include("product-import::admin.includes.articul")
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    @include("product-import::admin.includes.specifications")
                </div>
            </div>

        </div>

    </div>
</div>
@push('js-lib')
    <script type="text/javascript">
       function cmlSettings(){
            let array = new Map ([
                ["xmlRoot" , "Классификатор"],
                ["xmlPictureImportType" , "href"],

                ["xmlCategoryImportType" , "modify"],
                ["xmlCategoriesRoot" , "Группы"],
                ["xmlCategoriesRootAdd" , "Группы"],
                ["xmlCategory" , "Группа"],
                ["xmlCategoryIdType" , "element"],
                ["xmlCategoryId" , "Ид"],
                ["xmlCategoryParentType" , "element-tree"],
                ["xmlCategoryParentAttribute" , ""],
                ["xmlCategoryElementTreeName" , "Наименование"],
                ["xmlCategoryElementTreePicture" , ""],
                ["xmlCategoryElementTreePictureAdd" , ""],

                ["xmlProductImportType" , "modify"],
                ["xmlRootProductSelect" , "root-product"],
                ["xmlRootProduct" , "Каталог"],
                ["xmlProductsRoot" , "Товары"],
                ["xmlProduct" , "Товар"],
                ["xmlProductIdType" , "element"],
                ["xmlProductId" , "Ид"],
                ["xmlProductCategoryId" , "Группы"],
                ["xmlProductCategoryIdAdd" , "Ид"],
                ["xmlProductName" , "Наименование"],
                ["xmlProductPicture" , ""],
                ["xmlProductPictureAdd" , ""],
                ["xmlProductDescription" , "Описание"],
                ["xmlProductStore" , ""],

                ["xmlVariationType" , "file"],
                ["xmlProductPrice" , ""],
                ["xmlProductOldPrice" , ""],
                ["xmlVariationsRoot" , "ПакетПредложений"],
                ["xmlVariations" , "Предложения"],
                ["xmlVariationProductId" , "Ид"],
                ["xmlVariationProductTitle" , "Наименование"],
                ["xmlVariationPrices" , "Цены"],
                ["xmlVariationPriceElement" , "Цена"],
                ["xmlVariationPriceDescType" , "price"],
                ["xmlVariationPriceDesc" , "Единица"],
                ["xmlVariationPrice" , "ЦенаСайта"],
                ["xmlVariationOldPrice" , "ЦенаЗаЕдиницу"],
                ["xmlVariationCount" , "Количество"],

                ["xmlCodeType" , "product-element"],
                ["xmlCode" , "Артикул"],

                ["xmlPropType" , "list"],
                ["xmlPropListRoot" , "Свойства"],
                ["xmlPropListId" , "Ид"],
                ["xmlPropListName" , "Наименование"],
                ["xmlPropGroup" , "ЗначенияСвойств"],
                ["xmlProp" , "ЗначенияСвойства"],
                ["xmlPropId" , "Ид"],
                ["xmlPropValue" , "Значение"],
            ])
            array.forEach(function(value,key) {
                document.getElementById(key).value = value;
            });
           document.getElementById('xmlCategoryParentAttributeBlock').style.display = "none";
           document.getElementById('xmlCategoryElementTreeBlock').style.display = "block";
           document.getElementById('xmlRootProductBlock').style.display = "block";
           document.getElementById('xmlRootBlock').style.display = "none";
           document.getElementById('xmlVariationFileBlock').style.display = "block";
           document.getElementById('xmlVariationProductBlock').style.display = "none";
        }
       function ymlSettings() {
           let array = new Map ([
               ["xmlRoot" , "shop"],
               ["xmlPictureImportType" , "href"],

               ["xmlCategoryImportType" , "modify"],
               ["xmlCategoriesRoot" , "categories"],
               ["xmlCategoriesRootAdd" , ""],
               ["xmlCategory" , "category"],
               ["xmlCategoryIdType" , "attribute"],
               ["xmlCategoryId" , "id"],
               ["xmlCategoryParentType" , "attribute"],
               ["xmlCategoryParentAttribute" , "parentId"],
               ["xmlCategoryElementTreeName" , ""],
               ["xmlCategoryElementTreePicture" , ""],
               ["xmlCategoryElementTreePictureAdd" , ""],

               ["xmlProductImportType" , "modify"],
               ["xmlRootProductSelect" , "root"],
               ["xmlRootProduct" , ""],
               ["xmlProductsRoot" , "offers"],
               ["xmlProduct" , "offer"],
               ["xmlProductIdType" , "attribute"],
               ["xmlProductId" , "id"],
               ["xmlProductCategoryId" , "categoryId"],
               ["xmlProductCategoryIdAdd" , ""],
               ["xmlProductName" , "name"],
               ["xmlProductPicture" , "picture"],
               ["xmlProductPictureAdd" , ""],
               ["xmlProductDescription" , "description"],
               ["xmlProductStore" , "store"],

               ["xmlVariationType" , "product"],
               ["xmlProductPrice" , "price"],
               ["xmlProductOldPrice" , "oldprice"],
               ["xmlVariationsRoot" , ""],
               ["xmlVariations" , ""],
               ["xmlVariationProductId" , ""],
               ["xmlVariationProductTitle" , ""],
               ["xmlVariationPrices" , ""],
               ["xmlVariationPriceElement" , ""],
               ["xmlVariationPriceDescType" , ""],
               ["xmlVariationPriceDesc" , ""],
               ["xmlVariationPrice" , ""],
               ["xmlVariationOldPrice" , ""],
               ["xmlVariationCount" , ""],

               ["xmlCodeType" , "product-attribute"],
               ["xmlCode" , "id"],

               ["xmlPropType" , "param"],
               ["xmlPropListRoot" , ""],
               ["xmlPropListId" , ""],
               ["xmlPropListName" , ""],
               ["xmlPropGroup" , ""],
               ["xmlProp" , "param"],
               ["xmlPropId" , ""],
               ["xmlPropValue" , "name"],
           ])
           array.forEach(function(value,key) {
               document.getElementById(key).value = value;
           });
           document.getElementById('xmlCategoryParentAttributeBlock').style.display = "block";
           document.getElementById('xmlCategoryElementTreeBlock').style.display = "none";
           document.getElementById('xmlRootProductBlock').style.display = "none";
           document.getElementById('xmlRootBlock').style.display = "block";
           document.getElementById('xmlVariationFileBlock').style.display = "none";
           document.getElementById('xmlVariationProductBlock').style.display = "block";
       }
    </script>
@endpush