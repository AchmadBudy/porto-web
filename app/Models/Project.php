<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $casts = [
        'attributes' => 'array',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_categories')->withTimestamps();
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProjectGallery::class);
    }
}
