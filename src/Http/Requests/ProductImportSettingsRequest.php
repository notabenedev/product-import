<?php

namespace Notabenedev\ProductImport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImportSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'xml-root' => 'required|min:4|max:100',


            'xml-categories-root' => 'required|min:4|max:100',
            'xml-category' => 'required|min:4|max:100',
            'xml-category-id-type' => 'required|min:4|max:100',
            'xml-category-id' => 'required|min:2|max:100',

            'xml-category-parent-type' => 'required|min:4|max:100',
            'xml-category-parent-attribute' => 'required|min:4|max:100',
            'xml-category-element-tree-name' => 'required|min:4|max:100',
            'xml-category-element-tree-picture' => 'required|min:4|max:100',

            'xml-products-root' => 'required|min:4|max:100',
            'xml-product' => 'required|min:4|max:100',
            'xml-product-id-type' => 'required|min:4|max:100',
            'xml-product-id' => 'required|min:2|max:100',
            'xml-product-category-id' => 'required|min:4|max:100',
            'xml-product-category-id-add' => 'max:100',
            'xml-product-name' => 'required|min:2|max:100',
            'xml-product-picture' => 'required|min:2|max:100',
            'xml-product-description' => 'required|min:2|max:100',
            'xml-product-price' => 'required|min:2|max:100',
            'xml-product-old-price' => 'required|min:2|max:100',

            'xml-variation-type' => 'required|min:2|max:100',
            'xml-variations-root' => 'max:100',
            'xml-variation' => 'max:100',
            'xml-variation-prices' => 'max:100',
            'xml-variation-price-element' => 'max:100',
            'xml-variation-price' => 'max:100',
            'xml-code-type' => 'required|min:2|max:100',
            'xml-code' => 'required|min:2|max:100',
        ];
    }
}
