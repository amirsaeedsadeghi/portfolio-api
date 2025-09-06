<?php

namespace App\Http\Requests\V1\ProjectImage;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectImageRequest extends FormRequest
{
    use HandleCamelCaseInputAndErrors;

    private ?int $remaining = null;

    public function prepareForValidation(): void
    {
        /** @var \App\Repositories\Interfaces\ProjectRepositoryInterface $projectsRepo */
        $projectsRepo = app(\App\Repositories\Interfaces\ProjectRepositoryInterface::class);
        /** @var \App\Repositories\Interfaces\ProjectImageRepositoryInterface $imagesRepo */
        $imagesRepo = app(\App\Repositories\Interfaces\ProjectImageRepositoryInterface::class);


        $slug    = (string) $this->route('project');
        $project = $projectsRepo->findBySlug($slug);
        $current = $imagesRepo->countByProject($project->id);

        $this->remaining = max(0, 4 - $current);

        $this->merge(['project_id' => $project->id]);
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
        return [
            //
            'project_id' => 'required|integer|exists:projects,id',
            'images' => "required|array|min:1|max:{$this->remaining}",
            'images.*.file' => 'file|mimes:png,jpg,jpeg,webp|max:5120',
            'images.*.order' => 'sometimes|nullable|integer|min:1',
            'images.*.alt' => 'required|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'images.max' => "You can add maximum {$this->remaining} image(s) to your project."
        ];
    }
}
