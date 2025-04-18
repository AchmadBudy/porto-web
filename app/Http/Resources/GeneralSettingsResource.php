<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $name
 * @property-read string $title
 * @property-read string $bio
 * @property-read string $avatar
 * @property-read array<string> $skills
 * @property-read array<int,mixed> $socials
 */
class GeneralSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'skills' => $this->skills,
            'socials' => GeneralSettingsSocialsResource::collection($this->socials),
        ];
    }
}
