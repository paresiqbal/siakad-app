<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "username" => ["required", "string", "max:255"],
            "password" => ["required", "string", "min:255"],
        ];
    }

    protected function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag(),
        ], 400));
    }
}
