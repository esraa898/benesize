<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class ForgetPasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
            'new_password' => ['required', 'string', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
        ];
    }

    public function failedValidation( $validator)
    {
        $response = responseApi(403,$validator->errors()->first());
        throw (new ValidationException($validator, $response))->status(400);
    }
}
