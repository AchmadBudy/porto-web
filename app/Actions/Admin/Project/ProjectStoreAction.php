<?php

declare(strict_types=1);

namespace App\Actions\Admin\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ProjectStoreAction
{
    /**
     * Execute the action.
     *
     * @param array<string, mixed> $data
     *
     * @return Project
     */
    public function execute(array $data): Project
    {
        $projectData = $this->prepareProjectData($data);
        $project = Project::create([
            'name' => $projectData['name'],
            'description' => $projectData['description'],
            'slug' => Str::slug($projectData['name']),
            'image' => $projectData['image'],
            'attributes' => $projectData['attributes'] ?? null,
        ]);
        $this->attachCategories($project, $data['categories'] ?? []);
        $this->createGalleries($project, $data);

        return $project;
    }

    /**
     * Prepare project data for creation.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function prepareProjectData(array $data): array
    {
        $data['image'] = Storage::disk('public')->put('projects', $data['image']);
        return $data;
    }

    /**
     * Attach categories to the project.
     *
     * @param Project $project
     * @param array<int> $categories
     *
     * @return void
     */
    private function attachCategories(Project $project, array $categories): void
    {
        $project->categories()->attach($categories);
    }

    /**
     * Create galleries for the project.
     *
     * @param Project $project
     * @param array<string, mixed> $data
     *
     * @return void
     */
    private function createGalleries(Project $project, array $data): void
    {
        if (isset($data['galleries'])) {
            foreach ($data['galleries'] as $gallery) {
                try {
                    $image = Storage::disk('public')->put('projects/gallery', $gallery);
                    $project->galleries()->create(['image' => $image]);
                } catch (\Exception $e) {
                    Log::error('Error uploading gallery image: ' . $e->getMessage());
                }
            }
        }
    }
}
