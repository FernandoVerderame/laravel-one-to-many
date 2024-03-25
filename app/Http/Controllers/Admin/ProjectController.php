<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $query = Project::orderByDesc('updated_at')->orderByDesc('created_at');

        if ($filter) {
            $value = $filter === 'completed';
            $query->whereIsCompleted($value);
        }

        $projects = $query->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();

        return view('admin.projects.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:projects',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'is_completed' => 'nullable|boolean'
        ], [
            'title.required' => 'Title must be mandatory',
            'title.min' => 'Title must be at least :min characters',
            'title.max' => 'Title must be a maximum of :max characters',
            'title.unique' => 'There cannot be two projects with the same title',
            'image.image' => 'The added file is not an image',
            'image.mimes' => 'Valid extensions are .png, .jpg, .jpeg',
            'is_completed.boolean' => 'The value of the completed field is invalid',
            'description.required' => 'Description must be mandatory'
        ]);

        $data = $request->all();

        $project = new Project();

        $project->fill($data);
        $project->slug = Str::slug($project->title);
        $project->is_completed = Arr::exists($data, 'is_completed');

        // New file check
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension();

            // Save URL
            $img_url = Storage::putFileAs('project_images', $data['image'], "$project->slug.$extension");
            $project->image = $img_url;
        }

        $project->save();

        return to_route('admin.projects.show', $project->id)->with('type', 'success')->with('message', 'New project successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('projects')->ignore($project->id)],
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'is_completed' => 'nullable|boolean'
        ], [
            'title.required' => 'Title must be mandatory',
            'title.min' => 'Title must be at least :min characters',
            'title.max' => 'Title must be a maximum of :max characters',
            'title.unique' => 'There cannot be two projects with the same title',
            'image.image' => 'The added file is not an image',
            'image.mimes' => 'Valid extensions are .png, .jpg, .jpeg',
            'is_completed.boolean' => 'The value of the completed field is invalid',
            'description.required' => 'Description must be mandatory'
        ]);

        $data = $request->all();

        $data['slug'] = Str::slug($data['title']);
        $data['is_completed'] = Arr::exists($data, 'is_completed');

        // New file check
        if (Arr::exists($data, 'image')) {
            // Check if there is an old image
            if ($project->image) Storage::delete($project->image);

            $extension = $data['image']->extension();

            // Save URL
            $img_url = Storage::putFileAs('project_images', $data['image'], "{$data['slug']}.$extension");
            $project->image = $img_url;
        }

        $project->update($data);

        return to_route('admin.projects.show', $project->id)->with('type', 'success')->with('message', 'Project successfully edited!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('type', 'danger')->with('message', 'Project successfully deleted!');
    }


    // SOFT DELETE RUOTES

    public function trash()
    {
        $projects = Project::onlyTrashed()->get();

        return view('admin.projects.trash', compact('projects'));
    }

    public function restore(Project $project)
    {
        $project->restore();

        return to_route('admin.projects.index')->with('type', 'success')->with('message', 'Project successfully restored!');
    }

    public function drop(Project $project)
    {
        if ($project->image) Storage::delete($project->image);

        $project->forceDelete();

        return to_route('admin.projects.trash')->with('type', 'success')->with('message', 'Project permanently deleted!');
    }


    // Completion ruote
    public function toggleCompletion(Project $project)
    {
        $project->is_completed = !$project->is_completed;

        $project->save();

        $action = $project->is_completed ? 'completed' : 'saved as a draft';
        $type = $project->is_completed ? 'success' : 'info';

        return back()->with('type', $type)->with('message', "Project $project->title has been $action");
    }
}
