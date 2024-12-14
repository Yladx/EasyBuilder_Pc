<form action="{{ route('modules.update', $module->id) }}" method="POST" enctype="multipart/form-data" id="editModuleForm">
    @csrf
    @method('PUT')
    <style>
        .combined-input {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .combined-input input.form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            flex: 1;
        }

        .combined-input select.form-select {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            appearance: none; /* Hide the native dropdown */
            background-color: transparent; /* Transparent background */
            border: 1px solid #ced4da;
            cursor: pointer;
            width: 40px; /* Only as wide as the arrow */
            height: 100%; /* Match the height of the textbox */
            position: relative;
        }

        .form-select option {
            color: #000; /* Ensure options are visible */
        }
    </style>

    <div class="mb-3">
        <label for="tagInput" class="form-label fw-bold text-uppercase">Tag</label>
        <div class="combined-input">
            <!-- Textbox for adding or displaying selected tag -->
            <input
                type="text"
                id="tagInput"
                name="tag"
                class="form-control"
                value="{{ $module->tag }}"
                placeholder="Add new tag (or select)">
            <!-- Dropdown menu -->
            <select
                class="form-select"
                aria-label="Select Tag"
                onchange="document.getElementById('tagInput').value = this.value">
                <option value="">Select an existing tag</option>
                @foreach($existingTags as $existingTag)
                    <option value="{{ $existingTag }}" @if($module->tag == $existingTag) selected @endif>{{ $existingTag }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="title" class="form-label fw-bold text-uppercase">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ $module->title }}" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label fw-bold text-uppercase">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" required>{{ $module->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="video_src" class="form-label fw-bold text-uppercase">Upload Video</label>
        <input type="file" name="video_src" id="video_src" class="form-control" accept="video/*">
        @if($module->video_src)
            <small class="form-text text-muted">Current Video: <a href="{{ asset('storage/' . $module->video_src) }}" target="_blank">View</a></small>
        @endif
    </div>
    <div class="mb-3">
        <label for="information" class="form-label fw-bold text-uppercase ">Information</label>
        <textarea name="information" id="information" class="form-control">{{ $module->Information }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Module</button>
</form>
