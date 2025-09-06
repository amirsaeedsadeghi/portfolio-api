<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin();
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
            'name'=>'required|string|min:3|max:255',
            'email'=>'required|email|unique:users,email',
            'image'=>'sometimes|file|image|mimes:png,jpg,jpeg|max:5120',
            'password'=>[
                'required',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
                'confirmed'                
            ]
        ];
    }
}
