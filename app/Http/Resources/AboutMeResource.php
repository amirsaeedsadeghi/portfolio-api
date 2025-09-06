<?php

namespace App\Http\Resources;

use App\Traits\ConvertsSnakeToCamelOutput;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutMeResource extends JsonResource
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
            'type' => 'AboutMe',
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'location' => $this->location,
            'years_of_experience' => $this->years_of_experience,
            'language' => $this->language,
            'currently_learning' => $this->currently_learning,
            'availability' => $this->availability,
            'cv_url' => $this->cv_url,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
            'portfolio_image' => asset($this->portfolio_image),
        ];
    }
}
