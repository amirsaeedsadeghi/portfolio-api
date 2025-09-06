<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ConvertsSnakeToCamelOutput;
use Illuminate\Http\Resources\Json\JsonResource;

class StackResource extends JsonResource
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
            'type' => 'stacks',
            'id' => $this->id,
            'name' => $this->name,
            'image' => asset($this->image),
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
