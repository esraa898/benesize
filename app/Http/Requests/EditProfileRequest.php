<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EditProfileRequest extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'store_name' => 'required|string|max:200',
            'city_id' => 'required|integer|exists:cities,id',
            'phone' => 'required|string|max:20',
            'wallet_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:male,female'
        ];
    }

    public function failedValidation($validator)
    {
        $response = responseApi(500,$validator->errors()->first());
        throw (new ValidationException($validator, $response))->status(500);
    }
}
