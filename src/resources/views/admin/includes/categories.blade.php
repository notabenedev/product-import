
    <h4 class="mt-3">Категории</h4>

    <div class="form-group">
        <label class="text-secondary my-2" for="xmlCategoryImportType">Тип импорта категорий</label>
        <select type="text"
                id="xmlCategoryImportType"
                name="data-xml-category-import-type"
                class="form-control @error("xml-category-import-type") is-invalid @enderror">
            <option value="full" {{ old("xml-category-import-type", base_config()->get($name, "xml-category-import-type", "modify")) == "full" ? " selected" : "" }}>
                Полная выгрузка: скрытие всех отстутствующих категорий
            </option>
            <option value="modify" {{ old("xml-category-import-type", base_config()->get($name, "xml-category-import-type", "modify")) == "modify" ? " selected" : "" }}>
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
        <div id="xmlCategoryParentAttributeBlock" class="mt-3">
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
        <div id="xmlCategoryElementTreeBlock" class="mt-3">
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
                       value="{{ old("xml-category-element-tree-picture", base_config()->get($name, "xml-category-element-tree-picture", "")) }}"
                       class="form-control @error("xml-category-element-tree-picture") is-invalid @enderror">
                @error("xml-category-element-tree-picture")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror

                <label class="text-secondary my-2" for="xmlCategoryElementTreePictureAdd">xml элемент Картинка Категории (Дополнительный)</label>
                <input type="text"
                       id="xmlCategoryElementTreePictureAdd"
                       name="data-xml-category-element-tree-picture-add"
                       value="{{ old("xml-category-element-tree-picture-add", base_config()->get($name, "xml-category-element-tree-picture-add", "")) }}"
                       class="form-control @error("xml-category-element-tree-picture-add") is-invalid @enderror">
                @error("xml-category-element-tree-picture-add")
                <div class="invalid-feedback" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
