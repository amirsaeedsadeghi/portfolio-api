<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ConvertsSnakeToCamelOutput;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
{
    use ConvertsSnakeToCamelOutput;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'projectImages',
            'id' => $this->id,
            'image' => asset($this->image),
            'alt' => $this->alt,
            'order' => $this->order,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'link' => [
                'self' => route('projectImages.show', $this->id)
            ],
        ];
    }
}
