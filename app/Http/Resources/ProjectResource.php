<?php

namespace App\Http\Resources;

use App\Enums\AssetTypeEnum;
use App\Traits\ConvertsSnakeToCamelOutput;
use App\Traits\ResolvesAssetUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    use ConvertsSnakeToCamelOutput;
    use ResolvesAssetUrls;
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
            'primaryImage' => $this->assetUrl($this->primary_image, AssetTypeEnum::PROJECT),
            'client' => $this->client,
            'demoLink' => $this->demo_link,
            'github' => $this->github,
            'category' => $this->category,
            'order' => $this->order,
            'isActive' => $this->is_active,
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
