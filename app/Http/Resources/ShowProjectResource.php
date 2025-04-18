<?php

namespace App\Http\Resources;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $description
 * @property-read string $image
 * @property-read array<int, array{label: string, value: string}>|null $attributes
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class ShowProjectResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => url('storage/' . $this->image),
            'attributes' => $this->attributes ?? [],
            'categories' => ListCategoryResource::collection($this->whenLoaded('categories')),
            'galleries' => ListGalleryResource::collection($this->whenLoaded('galleries')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
