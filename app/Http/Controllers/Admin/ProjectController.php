<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Project\ProjectDeleteAction;
use App\Actions\Admin\Project\ProjectStoreAction;
use App\Actions\Admin\Project\ProjectUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\ProjectStoreRequest;
use App\Http\Requests\Admin\Project\ProjectUpdateRequest;
use App\Http\Resources\ListProjectResource;
use App\Http\Resources\SelectCategoryResource;
use App\Http\Resources\ShowProjectResource;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $projects = Project::query()
            ->with('categories')
            ->latest()
            ->paginate(10);

        return inertia('admin/projects/Index', [
            'projects' => ListProjectResource::collection($projects),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia(
            'admin/projects/Create',
            [
                'categories' => SelectCategoryResource::collection(
                    Category::query()
                        ->latest()
                        ->get()
                ),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request, ProjectStoreAction $action): RedirectResponse
    {
        $validate = $request->validated();
        $action->execute($validate);
        return to_route('admin.projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): Response
    {
        return inertia('admin/projects/Show', [
            'project' => ShowProjectResource::make(
                $project->load('categories', 'galleries')
            ),
            'categories' => SelectCategoryResource::collection(
                Category::query()
                    ->latest()
                    ->get()
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): Response
    {
        return inertia('admin/projects/Edit', [
            'currentProject' => ShowProjectResource::make(
                $project->load('categories', 'galleries')
            ),
            'categories' => SelectCategoryResource::collection(
                Category::query()
                    ->latest()
                    ->get()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project, ProjectUpdateAction $action): RedirectResponse
    {
        $validate = $request->validated();
        $action->execute($project, $validate);

        return to_route('admin.projects.index')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectDeleteAction $action): RedirectResponse
    {
        $action->execute($project);

        return to_route('admin.projects.index')->with('success', 'Project deleted successfully');
    }
}
