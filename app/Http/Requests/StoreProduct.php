<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
        $productID = ($this->get('id') != 'null' ? ',name,' . $this->get('id') : '');

        return [
            'category_id' => 'required|numeric|min:1',
            'name'        => 'required|unique:products' . $productID . '|max:64',
            'price'       => 'required|numeric|min:1',
            'image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
