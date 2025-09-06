<?php

namespace App\Http\Resources;

use App\Traits\HandleCamelCaseInputAndErrors;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMeResource extends JsonResource
{
    use HandleCamelCaseInputAndErrors;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'contactMe',
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'messageBody' => $this->message_body,
            'links' => [
                'self' => route('contact-me.show', $this->id)
            ]
        ];
    }
}
