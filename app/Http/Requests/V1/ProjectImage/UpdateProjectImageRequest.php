<?php

namespace App\Http\Requests\V1\ProjectImage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectImageRequest extends FormRequest
{
    protected ?int $id = null;

    public function prepareForValidation(): void
    {
        $this->id = $this->route('image');
        $this->merge(['_bind' => ['image' => $this->id]]);
    }

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
        $slug = (string) $this->route('project');
        $id = $this->id;
        return [
            //
            '_bind.image' => [
                'present',
                Rule::exists('project_images', 'id')->where(function ($q) use ($slug, $id) {
                    $q->where('id', $id)->whereIn('project_id', function ($sub) use ($slug) {
                        $sub->select('id')->from('projects')->where('slug', $slug);
                    });
                })
            ],
            'image' => 'sometimes|file|mimes:png,jpg,jpeg,webp|max:5120',
            'alt' => 'sometimes|string|min:3',
            'order' => 'sometimes|integer|min:1'
        ];
    }
    public function messages(): array
    {
        return [
            '_bind.image.exists' => 'This image is not related to this project.'
        ];
    }

    public function passedValidation(): void
    {
        $this->request->remove('_bind');
    }
}
