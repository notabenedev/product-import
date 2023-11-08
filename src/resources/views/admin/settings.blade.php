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
