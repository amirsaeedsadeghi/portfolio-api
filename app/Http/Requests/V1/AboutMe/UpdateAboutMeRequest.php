<?php

namespace App\Http\Requests\V1\AboutMe;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutMeRequest extends FormRequest
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
            'title' => 'sometimes|string|min:5|max:255',
            'summary' => 'sometimes|string|min:10|max:5000',
            'location' => 'sometimes|string|min:5',
            'years_of_experience' => 'sometimes|integer|min:1|max:100',
            'language' => 'sometimes|array',
            'language.*' => 'string',
            'currently_learning' => 'sometimes|string',
            'availability' => 'sometimes|boolean',
            'cv_url' => 'sometimes|url',
            'linkedin_url' => 'sometimes|url',
            'github_url' => 'sometimes|url',
            'portfolio_image' => 'sometimes|file|image|mimes:png,jpg,jpeg,webp|max:5120',
        ];
    }
}
