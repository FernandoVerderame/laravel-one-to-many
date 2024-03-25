@extends('layouts.app')

@section('title', 'Create Project')

@section('content')

<header class="pb-4 mb-4 mt-3 border-bottom">
    <h1>Create new Project</h1>
</header>

@include('includes.projects.form')

@endsection

@section('scripts')
    @vite('resources/js/image_preview.js')
    @vite('resources/js/slug.js')
@endsection