<?php

namespace App\Http\Requests\V1\AboutMe;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Foundation\Http\FormRequest;

class StoreAboutMeRequest extends FormRequest
{
    use HandleCamelCaseInputAndErrors;

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
            'title' => 'required|string|min:5|max:255',
            'summary' => 'required|string|min:10|max:5000',
            'location' => 'required|string|min:5',
            'years_of_experience' => 'required|integer|min:1|max:100',
            'language' => 'required|array',
            'language.*' => 'string',
            'currently_learning' => 'required|string',
            'availability' => 'nullable|boolean',
            'cv_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'portfolio_image' => 'nullable|file|image|mimes:png,jpg,jpeg,webp|size:5120',
        ];
    }
}
