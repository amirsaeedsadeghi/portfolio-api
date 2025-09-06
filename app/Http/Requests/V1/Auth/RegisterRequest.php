<?php

namespace App\Http\Requests\V1\Auth;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{

    use HandleCamelCaseInputAndErrors;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            //
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
                'confirmed'
            ],
            'name' => 'required|string|min:3|max:255',
            'image' => 'sometimes|file|image|mimes:png,jpg,jpeg|size:5120',
        ];
    }
}
