<div class="form-group">
    <h4 class="mt-3">Артикул</h4>
    <div class="form-group form-row">
        <div class="col-6">
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
        </div>
        <div class="col-6">
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