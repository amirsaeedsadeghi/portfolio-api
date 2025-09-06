<?php

namespace App\Http\Requests\V1\Project;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    use HandleCamelCaseInputAndErrors;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
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
            'title' => 'required|string|min:5|max:50',
            'summary' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:10|max:2000',
            'primary_image' => 'required|file|mimes:png,jpg,jpeg,webp|max:5120',
            'client' => 'sometimes|nullable|string|min:2',
            'demo_link' => 'sometimes|nullable|url',
            'github' => 'sometimes|nullable|url',
            'category' => 'required|string',
            'role' => 'required|string',
            'order' => 'sometimes|integer|min:1',
            'start_date' => 'sometimes|nullable|date',
            'stacks' => 'sometimes|nullable|array',
            'stacks.*' => 'integer|exists:stacks,id'
        ];
    }
}
