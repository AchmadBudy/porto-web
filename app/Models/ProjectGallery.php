<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $image
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class ProjectGallery extends Model
{
    /**
     * Get the project that owns the gallery.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
