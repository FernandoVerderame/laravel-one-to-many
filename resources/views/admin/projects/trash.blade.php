@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<header class="d-flex align-items-center justify-content-between pb-4 mb-4 mt-3 border-bottom">
    <h1>Deleted Projects</h1>

    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-success">Show active projects</a>
</header>

<table class="table table-hover table-dark border mb-4">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Slug</th>
            <th scope="col">Status</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th>
                <div class="d-flex justify-content-end">

                    {{-- TODO--}}
                    <a class="btn btn-sm btn-danger"><i class="fa-solid fa-trash me-2"></i>Clear trash</a>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>

        @forelse($projects as $project)
        <tr>
            <th scope="row">{{ $project->id }}</th>
            <td>{{ $project->title }}</td>
            <td>{{ $project->slug }}</td>
            <td>{{ $project->is_completed ? 'Completed' : 'Work in progress' }}</td>
            <td>{{ $project->created_at }}</td>
            <td>{{ $project->updated_at }}</td>
            <td>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pencil"></i></a>

                    <form action="{{ route('admin.projects.drop', $project->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-regular fa-trash-can"></i></button>
                    </form>

                    <form action="{{ route('admin.projects.restore', $project->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-arrows-rotate"></i></button>
                    </form>
                </div>
            </td>
        </tr>

        @empty 
            <tr>
                <td colspan="7">
                    <h3 class="text-center">There aren't any projects.</h3>
                </td>
            </tr>
        @endforelse

    </tbody>
</table>

@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection