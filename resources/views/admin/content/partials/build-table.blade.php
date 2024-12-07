<section class="container-fluid py-3">
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="buildTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="recommended-tab" data-bs-toggle="tab" data-bs-target="#recommended-section" type="button" role="tab" aria-controls="recommended-section" aria-selected="true">Recommended</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="userbuild-tab" data-bs-toggle="tab" data-bs-target="#userbuild-section" type="button" role="tab" aria-controls="userbuild-section" aria-selected="false">User</button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content">

        <!-- Recommended Builds Section -->
        <div class="tab-pane fade show active" id="recommended-section" role="tabpanel" aria-labelledby="recommended-tab">
            <!-- Table Section -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-transparent p-3 rounded mb-3 gap-3">
                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                            <div>
                                <label class="text-white me-2">Show</label>
                                <select class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="text-white ms-2">entries</label>
                            </div>
                            <div>
                                <label class="text-white me-2">Filter by:</label>
                                <select class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="">All</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="search-container flex-shrink-0" style="width: clamp(200px, 30vw, 300px);">
                            <input type="search" class="form-control form-control-sm" placeholder="Search Build..." aria-label="Search">
                        </div>
                    </div>
                    <div class="maintable table-responsive"  style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-dark table-striped table-hover" id="recommended-builds-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th scope="col">Actions</th>
                                    <th scope="col">Build ID</th>
                                    <th scope="col">Build Name</th>
                                    <th scope="col">Average Rating</th>
                                    <th scope="col">Is Published</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recommendedBuilds as $build)
                                <tr id="build-{{ $build->id }}" onclick="viewBuildDetails({{ $build->id }}, '{{ $build->build_name }}')" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adminModal">
                                    <td class="sticky-column bg-dark">
                                        <button class="btn btn-warning btn-sm" onclick="event.stopPropagation(); editBuild({{ $build->id }});" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('builds.delete', $build->id) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirmDeletion(event);">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" 
                                                  class="btn btn-danger"
                                                  onclick="event.stopPropagation();">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      </form>
                                    </td>
                                    <td>{{ $build->id }}</td>
                                    <td>{{ $build->build_name }}</td>
                                    <td>{{ $build->ratings_avg_rating ?? 'N/A' }}</td>
                                    <td>
                                        <span class="{{ $build->published ? 'text-success' : 'text-danger' }}">
                                            {{ $build->published ? 'Published' : 'Archived' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $build->total_views }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-white" id="recommended-table-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <nav aria-label="Table navigation">
                            <ul class="pagination mb-0" id="recommended-table-pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
            </div>
     
        <!-- User Builds Section -->
        <div class="tab-pane fade" id="userbuild-section" role="tabpanel" aria-labelledby="userbuild-tab">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-transparent p-3 rounded mb-3 gap-3">
                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                            <div>
                                <label class="text-white me-2">Show</label>
                                <select class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="text-white ms-2">entries</label>
                            </div>
                            <div>
                                <label class="text-white me-2">Filter by:</label>
                                <select class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="">All</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                    <option value="deleted">Deleted User</option>
                                </select>
                            </div>
                        </div>
                        <div class="search-container flex-shrink-0" style="width: clamp(200px, 30vw, 300px);">
                            <input type="search" class="form-control form-control-sm" placeholder="Search Build..." aria-label="Search">
                        </div>
                    </div>
                    <div class="maintable table-responsive"  style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-dark table-striped table-hover" id="user-build-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th scope="col">Actions</th>
                                <th scope="col">Build ID</th>
                                <th scope="col">Build Name</th>
                                <th scope="col">Average Rating</th>
                                <th scope="col">Builder Name</th>
                                <th scope="col">Is Published</th>
                                   <th>Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userBuilds as $build)
                            <tr id="build-{{ $build->id }}" onclick="viewBuildDetails({{ $build->id }}, '{{ $build->build_name }}')" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adminModal">

                                    <td>
                                        <form action="{{ route('builds.delete', $build->id) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirmDeletion(event);">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" 
                                                  class="btn btn-danger"
                                                  onclick="event.stopPropagation();">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      </form>
                                    </td>
                                    <td>{{ $build->id }}</td>
                                    <td>{{ $build->build_name }}</td>
                                    <td>{{ $build->ratings_avg_rating ?? 'N/A' }}</td>
                                    <td>{{ $build->user->name ?? 
                                       'Deleted User' 
                                       }}</td>
                                    <td>
                                        <span class="{{ $build->published ? 'text-success' : 'text-danger' }}">
                                            {{ $build->published ? 'Published' : 'Archived' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $build->total_views }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-white" id="user-table-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <nav aria-label="Table navigation">
                            <ul class="pagination mb-0" id="user-table-pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>


<button class="easypc-btn btn-md p-2 m-2" data-bs-toggle="modal" data-bs-target="#adminModal" id="createNewBuildButton">
    Create New Reccomended Build
</button>



<!-- JavaScript -->
<script>

function confirmDeletion(event) {
    // Prevent the form from triggering other parent actions
    event.preventDefault();
    event.stopPropagation();

    // Show a confirmation dialog
    const isConfirmed = confirm('Are you sure you want to delete this build?');
    
    if (isConfirmed) {
        // If confirmed, submit the form programmatically without triggering the modal
        event.target.closest('form').submit();
    }

    return false; // Prevent further event handling if not confirmed
}
    function viewBuildDetails(buildId, buildName) {
        const modalLabel = document.getElementById('adminModalLabel');
        const modalBody = document.getElementById('adminModalBody');

        modalLabel.textContent = buildName; // Set modal label to build name

        // Fetch the build details
        fetch(`/builds/buildinfo/${buildId}?admin=true`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load build details.');
                return response.text();
            })
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading build details:', error);
                modalBody.innerHTML = `
                    <p class="text-danger">Failed to load build details. Please try again later.</p>
                `;
            });
    }

    function editBuild(buildId) {
    const modalLabel = document.getElementById('adminModalLabel');
    const modalBody = document.getElementById('adminModalBody');

    modalLabel.textContent = 'Edit Build Details'; // Set modal title
    modalBody.innerHTML = '<p>Loading...</p>'; // Show loading state

    // Fetch the editable form
    fetch(`/builds/buildinfo/${buildId}?edit=true`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load editable form.');
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;

            initializeEditFunctionality(modalBody); // Pass modalBody as an argument
        })
        .catch(error => {
            console.error('Error loading editable form:', error);
            modalBody.innerHTML = '<p class="text-danger">Failed to load the editable form. Please try again later.</p>';
        });
}

function initializeEditFunctionality(modalBody) {
    console.log('Initializing edit functionality with modalBody:', modalBody);

    const editButton = modalBody.querySelector('#editButton');
    const saveButton = modalBody.querySelector('#saveButton');
    console.log('Edit Button:', editButton);
    console.log('Save Button:', saveButton);

    // Ensure the buttons exist before attaching event listeners
    if (editButton && saveButton) {
        // Toggle Edit Mode
        editButton.addEventListener('click', function () {
            console.log('Edit button clicked');
            const inputs = modalBody.querySelectorAll('input[readonly], textarea[readonly]');
            inputs.forEach(input => {
                console.log('Making input editable:', input);
                input.removeAttribute('readonly');
            });

            const checkboxes = modalBody.querySelectorAll('input[type="checkbox"][disabled]');
            checkboxes.forEach(checkbox => {
                console.log('Enabling checkbox:', checkbox);
                checkbox.removeAttribute('disabled');
            });

            // Toggle buttons
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
        });
    } else {
        console.error('Edit or Save button not found in modalBody.');
    }

    const form = modalBody.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (event) {

            });
        }
}

function updateTags() {
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const checkboxes = document.querySelectorAll('#checkbox-container input[type="checkbox"]');
    const isRecommendedBuild = window.location.href.includes('Recommended');

    // Define tag colors
    const tagColors = {
        'Recommended': 'success',      
                'Office': 'primary',
                'Gaming': 'danger',
                'School': 'warning'
    };

    // For recommended builds, ensure 'Recommended' tag is always present
    if (isRecommendedBuild) {
        const existingRecommendTag = Array.from(selectedTagsContainer.querySelectorAll('.badge')).find(badge => badge.textContent.trim() === 'Recommended');
        if (!existingRecommendTag) {
            const recommendBadge = document.createElement('span');
            recommendBadge.className = `badge bg-success text-white me-2`;
            recommendBadge.textContent = 'Recommended';
            recommendBadge.setAttribute('data-permanent', 'true');
            selectedTagsContainer.appendChild(recommendBadge);
        }
    }

    // Maintain existing tags and add/remove only based on checkbox state
    const existingTags = Array.from(selectedTagsContainer.querySelectorAll('.badge')).map(badge => badge.textContent.trim());

    checkboxes.forEach(checkbox => {
        const tagName = checkbox.value;

        // Skip removing 'Recommend' tag for recommended builds
        if (isRecommendedBuild && tagName === 'Recommended') {
            return;
        }

        if (checkbox.checked) {
            if (!existingTags.includes(tagName)) {
                const badge = document.createElement('span');
                const color = tagColors[tagName] || 'secondary';
                badge.className = `badge bg-${color} text-white me-2`;
                badge.textContent = tagName;
                selectedTagsContainer.appendChild(badge);
            }
        } else {
            const existingBadge = Array.from(selectedTagsContainer.querySelectorAll('.badge')).find(badge =>
                badge.textContent.trim() === tagName && !badge.hasAttribute('data-permanent')
            );
            if (existingBadge) {
                existingBadge.remove();
            }
        }
    });

    // Update the hidden input field to reflect current tags
    const updatedTags = Array.from(selectedTagsContainer.querySelectorAll('.badge')).map(badge => badge.textContent.trim());
    document.getElementById('tag').value = updatedTags.join(',');
}
function updateTags() {
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const checkboxes = document.querySelectorAll('#checkbox-container input[type="checkbox"]');
    const hiddenTagInput = document.getElementById('tag');

    // Define tag colors
    const tagColors = {
        'Recommended': 'success',      
                'Office': 'primary',
                'Gaming': 'danger',
                'School': 'warning'
    };

    // Ensure 'Recommend' tag is always present and cannot be cleared
    const existingRecommendTag = Array.from(selectedTagsContainer.querySelectorAll('.badge')).find(badge => badge.textContent.trim() === 'Recommended');
    if (!existingRecommendTag) {
        const recommendBadge = document.createElement('span');
        recommendBadge.className = `badge bg-${tagColors['Recommended']} text-white me-2`;
        recommendBadge.textContent = 'Recommended';
        recommendBadge.setAttribute('data-permanent', 'true');
        selectedTagsContainer.appendChild(recommendBadge);
    }

    // Maintain existing tags and add/remove based on checkbox state
    const existingTags = Array.from(selectedTagsContainer.querySelectorAll('.badge')).map(badge => badge.textContent.trim());

    checkboxes.forEach(checkbox => {
        const tagName = checkbox.value;

        if (checkbox.checked) {
            if (!existingTags.includes(tagName)) {
                const badge = document.createElement('span');
                const color = tagColors[tagName] || 'secondary';
                badge.className = `badge bg-${color} text-white me-2`;
                badge.textContent = tagName;
                selectedTagsContainer.appendChild(badge);
            }
        } else {
            const existingBadge = Array.from(selectedTagsContainer.querySelectorAll('.badge')).find(badge =>
                badge.textContent.trim() === tagName && !badge.hasAttribute('data-permanent')
            );
            if (existingBadge) {
                existingBadge.remove();
            }
        }
    });

    // Update the hidden input field to reflect current tags
    const updatedTags = Array.from(selectedTagsContainer.querySelectorAll('.badge')).map(badge => badge.textContent.trim());
    hiddenTagInput.value = updatedTags.join(',');
}

// Initialize the tags for new build form
document.addEventListener('DOMContentLoaded', () => {
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const hiddenTagInput = document.getElementById('tag');

    // Ensure the 'Recommend' tag is always present
    const recommendBadge = document.createElement('span');
    recommendBadge.className = 'badge bg-success text-white me-2';
    recommendBadge.textContent = 'Recommended';
    recommendBadge.setAttribute('data-permanent', 'true');
    selectedTagsContainer.appendChild(recommendBadge);

    // Initialize tags from the hidden input field value
    if (hiddenTagInput.value) {
        const preExistingTags = hiddenTagInput.value.split(',');
        preExistingTags.forEach(tag => {
            if (tag !== 'Recommended') {
                const color = tagColors[tag.trim()] || 'secondary';
                const badge = document.createElement('span');
                badge.className = `badge bg-${color} text-white me-2`;
                badge.textContent = tag.trim();
                selectedTagsContainer.appendChild(badge);
            }
        });
    }

    // Bind the updateTags function to manage checkbox state
    document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateTags);
    });

    // Update the hidden input field
    updateTags();
});


// Ensure pre-existing tags are preserved and managed
document.addEventListener('DOMContentLoaded', () => {
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const hiddenTagInput = document.getElementById('tag');

    // Initialize the tags from hidden input value if present
    if (hiddenTagInput.value) {
        const preExistingTags = hiddenTagInput.value.split(',');
        preExistingTags.forEach(tag => {
            const tagColors = {
              'Recommended': 'success',      
                'Office': 'primary',
                'Gaming': 'danger',
                'School': 'warning'
            };

            // Create a badge for each pre-existing tag
            const badge = document.createElement('span');
            const color = tagColors[tag.trim()] || 'secondary';
            badge.className = `badge bg-${color} text-white me-2`;
            badge.textContent = tag.trim();
            selectedTagsContainer.appendChild(badge);
        });
    }

    // Bind the updateTags function to manage changes dynamically
    document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateTags);
    });
});





document.getElementById('createNewBuildButton').addEventListener('click', function () {
   
    const modalElement = document.getElementById('adminModal');
    const modalLabel = document.getElementById('adminModalLabel');
    const modalBody = document.getElementById('adminModalBody');
    const modalDialog = modalElement.querySelector('.modal-dialog'); // Select the modal dialog
    
    // Set modal to extra-large size
   
    modalDialog.classList.add('modal-xl');
    
    modalLabel.textContent = 'Create New Build'; // Set modal title
    modalBody.innerHTML = '<p>Loading...</p>'; // Show loading spinner

    // Fetch the "Create New Build" form
    fetch('/recommended-build-form')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load form.');
            }
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html; // Inject the form into the modal body

              // Dynamically load the external script


    const script = document.createElement('script');
    script.src = '/js/admin/buildcompatability.js'; // Path to your script file
    script.type = 'text/javascript';
    script.onload = () => {
        console.log('Script loaded and executed successfully!');
    };
    script.onerror = () => {
        console.error('Error loading the script!');
    };
    document.body.appendChild(script); // Append script to the document
        })
        .catch(error => {
            console.error('Error loading form:', error);
            modalBody.innerHTML = '<p class="text-danger">Failed to load form. Please try again later.</p>';
        });
});
$(document).on('submit', '#recommendedBuildForm', function(e) {
    e.preventDefault();
        
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                title: response.title,
                text: response.message,
                icon: response.type,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Close modal and refresh table
                    $('#adminModal').modal('hide');
                    location.reload();
                }
            });
        },
        error: function(xhr) {
            let response = xhr.responseJSON;
            Swal.fire({
                title: response.title || 'Error!',
                text: response.message || 'Something went wrong!',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});


</script>
