<?php

namespace App\Http\Requests\V1\Stack;

use Illuminate\Foundation\Http\FormRequest;

class StoreStackRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:50',
            'image' => 'required|file|mimes:png,jpg,jpeg.webp|max:5120'
        ];
    }
}
