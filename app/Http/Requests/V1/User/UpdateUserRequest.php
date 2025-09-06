<?php

namespace App\Http\Requests\V1\User;

use App\Enums\UserRoleEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
        $userParam = $this->route('user'); 
        $userId = $userParam instanceof \App\Models\User ? $userParam->getKey() : $userParam;
        return [
            //
            'name' => 'sometimes|string|min:3|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users','email')->ignore($userId,'id')
            ],
            'password' => [
                'sometimes', 
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
                'confirmed'
            ],
            'image' => 'sometimes|file|image|mimes:png,jpg,jpeg|max:5120',
            'role' => 'sometimes|in:user,'.implode(',',UserRoleEnum::values()),
        ];
    }

    public function prepareForValidation(): void
    {
        if (!$this->user()->isAdmin()) {
            $this->request->remove('role');
        }
    }
}
