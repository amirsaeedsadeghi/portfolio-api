<?php

namespace App\Http\Resources;

use App\Enums\AssetTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ConvertsSnakeToCamelOutput;
use App\Traits\ResolvesAssetUrls;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
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
            'type' => 'projectImages',
            'id' => $this->id,
            'image' => $this->assetUrl($this->image, AssetTypeEnum::PROJECT),
            'alt' => $this->alt,
            'order' => $this->order,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'link' => [
                'self' => route('projectImages.show', $this->id)
            ],
        ];
    }
}
