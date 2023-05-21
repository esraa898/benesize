<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CreatePasswordRequest extends FormRequest
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
            'password' => ['string','confirmed',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        ];
    }

    public function failedValidation( $validator)
    {
        $response = responseApi(403,$validator->errors()->first());
        throw (new ValidationException($validator, $response))->status(400);
    }

    
}
