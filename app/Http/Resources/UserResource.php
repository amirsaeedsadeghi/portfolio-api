<?php

namespace App\Http\Resources;

use App\Enums\UserRoleEnum;
use App\Traits\ConvertsSnakeToCamelOutput;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type' => 'users',
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            $this->mergeWhen(
                $request->user()?->isAdmin(),
                ['role' => $this->role ?? UserRoleEnum::GUEST]
            ),
            $this->mergeWhen($request->routeIs('users.*'), [
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
            'links' => [
                'self' => route('users.show', $this->id)
            ]
        ];
    }
}
