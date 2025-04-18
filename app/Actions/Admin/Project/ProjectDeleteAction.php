<?php

declare(strict_types=1);

namespace App\Actions\Admin\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class ProjectDeleteAction
{
    /**
     * Execute the action.
     *
     * @param Project $project
     *
     * @return void
     */
    public function execute(Project $project): void
    {
        //Delete the project image
        Storage::disk('public')->delete($project->image);

        // delete the project galleries
        foreach ($project->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image);
            $gallery->delete();
        }

        // delete the project
        $project->delete();
    }
}
