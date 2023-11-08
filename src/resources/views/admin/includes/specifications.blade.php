<div class="form-group">
    <h4 class="mt-3">Свойства Товара</h4>
    <label class="text-secondary my-2" for="xmlPropType">Тип Свойств</label>
    <select type="text"
            id="xmlPropType"
            name="data-xml-prop-type"
            class="form-control @error("xml-prop-type") is-invalid @enderror">
        <option value="list"
                {{ old("xml-prop-type", base_config()->get($name, "xml-prop-type", "list")) == "list" ? " selected" : "" }}>
            справочник Свойств внутри Корневого xml-элемента ( ИД - Название свойства)
        </option>
        <option value="param"
                {{ old("xml-prop-type", base_config()->get($name, "xml-prop-type", "list")) == "param" ? " selected" : "" }}>
            xml-элементы внутри xml-элемента Товара, где атрибут - имя свойства
        </option>
        <option value="list-element"
                {{ old("xml-prop-type", base_config()->get($name, "xml-prop-type", "list")) == "list-element" ? " selected" : "" }}>
            справочник Названий Свойств внутри Корневого xml-элемента  (Названия xml-элементов без ИД)
        </option>
    </select>
    <div class="propListBlock">
        <label class="text-secondary my-2" for="xmlPropListRoot">Корневой xml элемент Справочника Свойств</label>
        <input type="text"
               id="xmlPropListRoot"
               name="data-xml-prop-list-root"
               value="{{ old("xml-prop-list-root", base_config()->get($name, "xml-prop-list-root", "")) }}"
               class="form-control @error("xml-prop-list-root") is-invalid @enderror">
        @error("xml-prop-list-root")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror
        <label class="text-secondary my-2" for="xmlPropListId">xml элемент ИД Свойства в Справочнике</label>
        <input type="text"
               id="xmlPropListId"
               name="data-xml-prop-list-id"
               value="{{ old("xml-prop-list-id", base_config()->get($name, "xml-prop-list-id", "")) }}"
               class="form-control @error("xml-prop-list-id") is-invalid @enderror">
        @error("xml-prop-list-id")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror
        <label class="text-secondary my-2" for="xmlPropListName">xml элемент Названия Свойства в Справочнике</label>
        <input type="text"
               id="xmlPropListName"
               name="data-xml-prop-list-name"
               value="{{ old("xml-prop-list-name", base_config()->get($name, "xml-prop-list-name", "")) }}"
               class="form-control @error("xml-prop-list-name") is-invalid @enderror">
        @error("xml-prop-list-name")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="propBlock">
        <label class="text-secondary my-2" for="xmlPropGroup">xml элемент Группы Свойств в xml-элементе Товара</label>
        <input type="text"
               id="xmlPropGroup"
               name="data-xml-prop-group"
               value="{{ old("xml-prop-group", base_config()->get($name, "xml-prop-group", "")) }}"
               class="form-control @error("xml-prop-group") is-invalid @enderror">
        @error("xml-prop-group")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>
    <label class="text-secondary my-2" for="xmlProp">xml элемент Свойства в xml-элементе Товара</label>
    <input type="text"
           id="xmlProp"
           name="data-xml-prop"
           value="{{ old("xml-prop", base_config()->get($name, "xml-prop", "")) }}"
           class="form-control @error("xml-prop") is-invalid @enderror">
    @error("xml-prop")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
    <label class="text-secondary my-2" for="xmlPropId">xml элемент|атрибут ID Свойства в xml-элементе Товара</label>
    <input type="text"
           id="xmlPropId"
           name="data-xml-prop-id"
           value="{{ old("xml-prop-id", base_config()->get($name, "xml-prop-id", "")) }}"
           class="form-control @error("xml-prop-id") is-invalid @enderror">
    @error("xml-prop-id")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
    <label class="text-secondary my-2" for="xmlPropValue">xml элемент|атрибут Значения Свойства в xml-элементе Товара</label>
    <input type="text"
           id="xmlPropValue"
           name="data-xml-prop-value"
           value="{{ old("xml-prop-value", base_config()->get($name, "xml-prop-value", "")) }}"
           class="form-control @error("xml-prop-value") is-invalid @enderror">
    @error("xml-prop-value")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror

</div>