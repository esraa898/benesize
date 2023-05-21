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
            'email' => 'required|string|email|max:100|unique:users,email,' . auth()->id(),
            'phone' => 'required|string|max:20',
            'gender' => 'required|string|in:male,female',
            'address' => 'required|string|max:255',
            'lat' => 'string|required',
            'lang' => 'string|required',
            'image' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required'
        ];
    }

    public function failedValidation($validator)
    {
        $response = responseApi(500,$validator->errors()->first());
        throw (new ValidationException($validator, $response))->status(500);
    }
}
