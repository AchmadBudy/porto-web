<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralSettingsResource;
use App\Http\Resources\ListCategoryResource;
use App\Http\Resources\ListProjectResource;
use App\Http\Resources\ShowProjectResource;
use App\Models\Category;
use App\Models\Project;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    /**
     * Display the portfolio home page with projects list
     */
    public function index(GeneralSettings $settings)
    {
        $projects = Project::query()
            ->with('categories')
            ->latest()
            ->paginate(9); // Show more projects on the portfolio page

        $categories = Category::query()
            ->latest()
            ->get();

        return Inertia::render('portfolio/Index', [
            'projects' => ListProjectResource::collection($projects),
            'categories' => ListCategoryResource::collection($categories),
            'generalSettings' => GeneralSettingsResource::make($settings),
        ]);
    }

    /**
     * Display all projects with pagination
     */
    public function all(GeneralSettings $settings)
    {
        $projects = Project::query()
            ->with('categories')
            ->latest()
            ->paginate(12); // Show more projects on the all projects page

        $categories = Category::query()
            ->latest()
            ->get();

        return Inertia::render('portfolio/All', [
            'projects' => ListProjectResource::collection($projects),
            'categories' => ListCategoryResource::collection($categories),
            'generalSettings' => GeneralSettingsResource::make($settings),
        ]);
    }

    /**
     * Display the specified project details
     */
    public function show(Project $project, GeneralSettings $settings)
    {
        // Get related projects (projects with similar categories)
        $categoryIds = $project->categories->pluck('id');

        $relatedProjects = Project::query()
            ->with('categories')
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->where('id', '!=', $project->id) // Exclude current project
            ->latest()
            ->take(3) // Limit to 3 related projects
            ->get();

        return Inertia::render('portfolio/Show', [
            'project' => ShowProjectResource::make(
                $project->load('categories', 'galleries')
            ),
            'relatedProjects' => ShowProjectResource::collection($relatedProjects),
            'generalSettings' => GeneralSettingsResource::make($settings),
        ]);
    }
}
