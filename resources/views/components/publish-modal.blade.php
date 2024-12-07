<!-- Publish Build Modal -->
<div class="modal fade" id="publishBuildModal" tabindex="-1" aria-labelledby="publishBuildModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publishBuildModalLabel">Save Your Build</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="publishBuildForm">
                    <div class="mb-3">
                        <label for="build_name" class="form-label">Build Name*</label>
                        <input type="text" class="form-control" id="build_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="accessories" class="form-label">Accessories (Optional)</label>
                        <textarea class="form-control" id="accessories" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="build_note" class="form-label">Build Note (Optional)</label>
                        <textarea 
                            class="form-control" 
                            id="build_note" 
                            rows="3" 
                            maxlength="70" 
                            placeholder="You can enter up to 250 characters."
                        ></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <div id="checkbox-container" class="d-flex flex-wrap gap-2 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="School" id="tag1">
                                <label class="form-check-label" for="tag1">School</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Gaming" id="tag2">
                                <label class="form-check-label" for="tag2">Gaming</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Office" id="tag3">
                                <label class="form-check-label" for="tag3">Office</label>
                            </div>
                        </div>
                        <div id="selected-tags-container"></div>
                        <input type="hidden" id="tag" name="tag" required>
                        <div id="tag-error" class="text-danger" style="display: none;">Please select at least one tag.</div>
                    </div>
                    
                 
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="is_publish">
                                DO you want to publish this build?
                             </label>
                            <input class="form-check-input" type="checkbox" id="is_publish">
                           
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBuildBtn">Save Build</button>
            </div>
        </div>
    </div>
</div>
   
<script>
    document.getElementById('saveBuildBtn').addEventListener('click', function (event) {
        // Get all checkboxes
        const checkboxes = document.querySelectorAll('#checkbox-container .form-check-input');
        const selectedTags = [];
        
        // Collect selected tags
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedTags.push(checkbox.value);
            }
        });
        
        // Display error if no tags are selected
        if (selectedTags.length === 0) {
            event.preventDefault(); // Prevent form submission
            document.getElementById('tag-error').style.display = 'block';
        } else {
            document.getElementById('tag-error').style.display = 'none';
            document.getElementById('tag').value = selectedTags.join(', ');
        }
    });
</script>