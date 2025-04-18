<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $name
 * @property-read string $url
 * @property-read string $imageUrl
 */
class GeneralSettingsSocialsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this['name'],
            'url' => $this['url'],
            'previewImageUrl' => url('storage/' . $this['imageUrl']),
        ];
    }
}
