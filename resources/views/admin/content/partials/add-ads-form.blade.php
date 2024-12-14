<form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="label" class="form-label fw-bold text-uppercase">Ad Label</label>
            <input type="text" name="label" id="label" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label fw-bold text-uppercase">Caption</label>
            <textarea name="caption" id="caption" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="access_link" class="form-label fw-bold text-uppercase">Access Link</label>
            <input type="url" name="access_link" id="access_link" class="form-control">
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label fw-bold text-uppercase">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label fw-bold text-uppercase">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="image">Image</option>
                <option value="video">Video</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="adMedia" class="form-label fw-bold text-uppercase">Advertisement Media</label>
            <input type="file" class="form-control" id="adMedia" name="src" accept="image/*,video/*" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="advertise" id="advertise" value="1">
            <label class="form-check-label" for="advertise">Advertise</label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Advertisement</button>
    </div>
</form>
