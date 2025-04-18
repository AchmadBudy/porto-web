<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $image
 * @property-read string $slug
 * @property-read string $description
 */
class ListProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => url('storage/' . $this->image),
            'slug' => $this->slug,
            'description' => Str::limit(strip_tags($this->description), 200),
            'categories' => ListCategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
