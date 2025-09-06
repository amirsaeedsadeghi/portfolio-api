<?php

namespace App\Http\Resources;

use App\Traits\ConvertsSnakeToCamelOutput;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
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
            'type' => 'projects',
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'primaryImage' => asset($this->primary_image),
            'client' => $this->client,
            'demoLink' => $this->demo_link,
            'github' => $this->github,
            'category' => $this->category,
            'order' => $this->order,
            'slug' => $this->slug,
            'role' => $this->role,
            'startDate' => $this->start_date,
            'stacks' => StackResource::collection($this->whenLoaded('stacks')),
            'images' => ProjectImageResource::collection($this->whenLoaded('images')),
            'links' => [
                'self' => route('projects.show', $this->slug),
            ]


        ];
    }
}
