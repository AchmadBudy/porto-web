<?php

declare(strict_types=1);

namespace App\Actions\Admin\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class ProjectUpdateAction
{
    /**
     * Execute the action.
     *
     * @param Project $project
     * @param array<string, mixed> $data
     *
     * @return Project
     */
    public function execute(Project $project, array $data): Project
    {
        $projectData = $this->prepareProjectData($data, $project);
        $project->update([
            'name' => $projectData['name'],
            'description' => $projectData['description'],
            'slug' => $project->slug,
            'image' => $projectData['image'],
            'attributes' => $projectData['attributes'] ?? [],
        ]);
        $this->syncCategories($project, $data['categories'] ?? []);
        $this->updateGalleries($project, $data);

        return $project;
    }

    /**
     * Prepare project data for creation.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function prepareProjectData(array $data, Project $project): array
    {
        //    check if image is present
        if (isset($data['image'])) {
            $data['image'] = Storage::disk('public')->put('projects', $data['image']);

            // delete old image
            Storage::disk('public')->delete($project->image);
        } else {
            $data['image'] = $project->image;
        }
        return $data;
    }

    /**
     * Sync categories to the project.
     *
     * @param Project $project
     * @param array<int> $categories
     *
     * @return void
     */
    private function syncCategories(Project $project, array $categories): void
    {
        $project->categories()->sync($categories);
    }


    /**
     * Update galleries for the project.
     *
     * @param Project $project
     * @param array<string, mixed> $data
     *
     * @return void
     */
    private function updateGalleries(Project $project, array $data): void
    {
        // check if there are old galleries and check if data is different from on the database
        if (isset($data['old_galleries'])) {
            $project->load('galleries');
            /** @var array<array{id: int}> $oldGalleriesData */
            $oldGalleriesData = $data['old_galleries'];
            $oldgalleriesCollection = collect($oldGalleriesData);
            $oldgalleries = $project->galleries;
            $oldgalleriesNeedToDelete = $oldgalleries->filter(function ($gallery) use ($oldgalleriesCollection) {
                return ! $oldgalleriesCollection->contains('id', $gallery->id);
            });
            foreach ($oldgalleriesNeedToDelete as $gallery) {
                Storage::disk('public')->delete($gallery->image);
            }
            $project->galleries()->whereIn('id', $oldgalleriesNeedToDelete->pluck('id'))->delete();
        }

        if (isset($data['galleries'])) {
            foreach ($data['galleries'] as $gallery) {
                $image = Storage::disk('public')->put('projects/gallery', $gallery);
                $project->galleries()->create(['image' => $image]);
            }
        }
    }
}
