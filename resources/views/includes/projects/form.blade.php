@if($project->exists)
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @method('PUT')
@else 
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif

    @csrf

    <div class="row">

        <div class="col-6">
            <div class="mb-4">
                <label for="title" class="form-label h3">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @elseif(old('title', '')) is-valid @enderror" placeholder="Ex.: Laravel DC Comics" value="{{ old('title', $project->title) }}" required>
                @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @else 
                <div class="form-text">
                    Add project's title
                </div>
                @enderror
            </div>
        </div>
        
        <div class="col-6">
            <div class="mb-4">
                <label for="slug" class="form-label h3">Slug</label>
                <input type="text" id="slug" class="form-control" value="{{ Str::slug(old('title', $project->title)) }}" disabled> 
            </div>   
        </div>

        <div class="col-12">
            <div class="mb-4">
                <label for="description" class="form-label h3">Description</label>
                <textarea type="text" name="description" id="description" class="form-control @error('description') is-invalid @elseif(old('description', '')) is-valid @enderror" placeholder="Project description..." rows="10" required>{{ old('description', $project->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @else 
                <div class="form-text">
                    Add project's description
                </div>
                @enderror
            </div>
        </div>

        <div class="col-10">
            <div class="mb-4">
                <label for="image" class="form-label h3">URL Image</label>

                <div @class(['input-group', 'd-none' => !$project->image]) id="previous-image-field">
                    <button class="btn btn-outline-secondary" type="button" id="change-image-button">Change image</button>
                    <input type="text" class="form-control" value="{{ old('image', $project->image) }}" disabled>
                </div>

                <input type="file" name="image" id="image" class="form-control @if($project->image) d-none @endif @error('image') is-invalid @elseif(old('image', '')) is-valid @enderror" placeholder="Ex.: https:://...">
                
                @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @else 
                <div class="form-text">
                    Upload image file
                </div>
                @enderror
            </div>
        </div>
        <div class="col-1  d-flex align-items-center">
            <div class="">
                <img src="{{ old('image', $project->image) 
                ? $project->printImage() 
                : 'https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=' }}" class="img-fluid" alt="{{ $project->image ? $project->title : 'preview' }}" id="preview">
            </div>
        </div>
        <div class="col-1 d-flex align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_completed" name="is_completed" value="1" @if(old('is_completed', $project->is_completed)) checked @endif>
                <label class="form-check-label" for="is_completed">Completed</label>
            </div>
        </div>

    </div>

    <footer class="d-flex justify-content-between align-items-center pt-4 border-top">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Back to projects</a>
    
        <div>
            <button type="reset" class="btn btn-primary me-2"><i class="fa-solid fa-eraser me-2"></i>Reset</button>
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
        </div>
    </footer>

</form>