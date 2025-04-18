<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read string $slug
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class Project extends Model
{
    protected $casts = [
        'attributes' => 'array',
    ];

    /**
     * Get the categories that belong to the project.
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_categories')->withTimestamps();
    }

    /**
     * Get the project galleries for the project.
     *
     * @return HasMany<ProjectGallery, $this>
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(ProjectGallery::class);
    }
}
