@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div class="card mt-5 mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        {{ $project->title }}
    </div>
    <div class="card-body">
        <div class="row">
            @if($project->image)
                <div class="col-3">
                    <img src="{{ $project->printImage() }}" class="img-fluid" alt="{{ $project->title }}">
                </div>
            @endif

            <div class="col">
                <h5 class="card-title mb-3">{{ $project->title }}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ $project->created_at }}</h6>
                <p class="card-text">{{ $project->description }}</p>
            </div>
        </div>
    </div>
</div>

<footer>
    <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Back to home</a>
</footer>


@endsection