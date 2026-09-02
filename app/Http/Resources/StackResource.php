<?php

namespace App\Http\Resources;

use App\Enums\AssetTypeEnum;
use App\Traits\ConvertsSnakeToCamelOutput;
use App\Traits\ResolvesAssetUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StackResource extends JsonResource
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
            'type' => 'stacks',
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->assetUrl($this->image, AssetTypeEnum::STACK),
            $this->mergeWhen($request->routeIs('stacks.*'), [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]),
            'included' => [
                'new ProjectResource($this->project)'
            ],
            'links' => [
                'self' => route('stacks.show', $this->id),
            ]
        ];
    }
}
