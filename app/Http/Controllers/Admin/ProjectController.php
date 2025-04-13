<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListProjectResource;
use App\Http\Resources\SelectCategoryResource;
use App\Http\Resources\ShowProjectResource;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function create()
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
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'unique:projects'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'max:2048'],
            'attributes' => ['nullable', 'array'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],
            'galleries' => ['nullable', 'array'],
            'galleries.*' => ['required', 'image', 'max:2048'],
        ]);
        $validate['slug'] = Str::slug($validate['name']);
        $validate['image'] = Storage::disk('public')->put('projects', $validate['image']);

        $project = Project::create([
            'name' => $validate['name'],
            'slug' => $validate['slug'],
            'description' => $validate['description'],
            'image' => $validate['image'],
            'attributes' => $validate['attributes'],
        ]);
        $project->categories()->attach($validate['categories']);
        if ($request->hasFile('galleries')) {
            foreach ($validate['galleries'] as $gallery) {
                $image = Storage::disk('public')->put('projects/gallery', $gallery);
                $project->galleries()->create(['image' => $image]);
            }
        }

        return to_route('admin.projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
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
    public function edit(Project $project)
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
    public function update(Request $request, Project $project)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'unique:projects,name,' . $project->id],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'attributes' => ['nullable', 'array'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],
            'galleries' => ['nullable', 'array'],
            'galleries.*' => ['required', 'image', 'max:2048'],
            'old_galleries' => ['nullable', 'array'],
        ]);
        $validate['slug'] = Str::slug($validate['name']);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($project->image);
            $validate['image'] = Storage::disk('public')->put('projects', $validate['image']);
        }

        $project->update([
            'name' => $validate['name'],
            'slug' => $validate['slug'],
            'description' => $validate['description'],
            'image' => $validate['image'] ?? $project->image,
            'attributes' => $validate['attributes'],
        ]);
        $project->categories()->sync($validate['categories']);

        // check if there are old galleries and check if data is different from on the database
        if ($request->has('old_galleries')) {
            $project->load('galleries');
            $oldgalleriesCollection = collect($validate['old_galleries']);
            $oldgalleries = $project->galleries;
            $oldgalleriesNeedToDelete = $oldgalleries->filter(function ($gallery) use ($oldgalleriesCollection) {
                return ! $oldgalleriesCollection->contains(key: 'id', value: $gallery->id);
            });
            foreach ($oldgalleriesNeedToDelete as $gallery) {
                Storage::disk('public')->delete($gallery->image);
            }
            $project->galleries()->whereIn('id', $oldgalleriesNeedToDelete->pluck('id'))->delete();
        }

        if ($request->hasFile('galleries')) {
            foreach ($validate['galleries'] as $gallery) {
                $image = Storage::disk('public')->put('projects/gallery', $gallery);
                $project->galleries()->create(['image' => $image]);
            }
        }

        return to_route('admin.projects.index')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // delete project image
        Storage::disk('public')->delete($project->image);
        // delete project galleries
        $project->galleries->each(function ($gallery) {
            Storage::disk('public')->delete($gallery->image);
        });
        $project->delete();

        return to_route('admin.projects.index')->with('success', 'Project deleted successfully');
    }
}
