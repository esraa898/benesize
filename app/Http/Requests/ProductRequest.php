<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
            'name' => 'required|max:100',
            'description' => 'required|string',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0|gt:min_price',
            'is_on_sale' => 'required|in:0,1',
            'is_new' => 'required|in:0,1',
            'is_new_arrival' => 'required|in:0,1',
            'is_best_seller' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'array',
            'colors' => 'array',
        ];
    }

    public function failedValidation($validator)
    {
        $response = responseApi(500, $validator->errors()->first());
        throw (new ValidationException($validator, $response))->status(500);
    }
}
