<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavbarItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'NavbarItems',
            'id' => $this->id,
            'label' => $this->label,
            'link' => $this->link,
            'order' => $this->order,
            'links' => [
                'self' => route('navbar-items.show', $this->id),
            ]
        ];
    }
}
