<form  action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data" id="addModuleForm">
    @csrf


    <div class="mb-3">
        <label for="tagInput" class="form-label">Tag</label>
        <div class="combined-input">
            <!-- Textbox for adding or displaying selected tag -->
            <input
                type="text"
                id="tagInput"
                name="tag"
                class="form-control"
                placeholder="Add new tag (or select)">
            <!-- Dropdown menu -->
            <select
                class="form-select"
                aria-label="Select Tag"
                onchange="document.getElementById('tagInput').value = this.value">
                <option value="">Select an existing tag</option>
                @foreach($existingTags as $existingTag)
                    <option value="{{ $existingTag }}">{{ $existingTag }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="video_src" class="form-label">Upload Video</label>
        <input type="file" name="video_src" id="video_src" class="form-control" accept="video/*">
    </div>
    <div class="mb-3">
        <label for="information" class="form-label">Information</label>
        <textarea name="information" id="information" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Module</button>
</form>

<script>

</script>
