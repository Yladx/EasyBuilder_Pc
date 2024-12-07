<x-app-layout>

    <div class="container mt-4">
        <h3 class="text-start ">Your Builds</h3>

        <hr>
        @if($userbuilds->isEmpty())
            <div class="col-12">
                <p class="text-center">You haven't created any builds yet.</p>
            </div>
        @else
            @foreach($userbuilds as $userbuild)
                <div class="build-container mb-4">
                    <!-- Build Image -->
                    <div class="build-image" style="background-image: url('{{ $userbuild->image ? asset('storage/' . $userbuild->image) : asset('placeholder.png') }}');"></div>

                    <!-- Build Content -->
                    <div class="build-content">
                        <div class="default-content">
                            <div class="build-date">{{ $userbuild->created_at->format('d M Y') }}</div>
                            <h3 class="build-title">{{ $userbuild->build_name }}</h3>
                            <div class="build-tags">
                            @foreach(explode(',', $userbuild->tag) as $tag)
                                                @php
                                                    // Define specific colors for tags
                                                    $tagColors = [
                                                        'Recommended' => 'success', // Green
                                                        'Editing' => 'info',       // Light Blue
                                                        'Office' => 'danger',      // Red
                                                        'Gaming' => 'primary'      // Blue
                                                    ];
                                                    // Determine the color class, default to 'secondary' (gray) if no match
                                                    $color = $tagColors[trim($tag)] ?? 'secondary';
                                                @endphp
                                                <!-- Display tag with dynamic color -->
                                                <span class="badge bg-{{ $color }} text-white">{{ trim($tag) }}</span>
                                            @endforeach
                            </div>
                            <p class="build-description">{{ \Illuminate\Support\Str::limit($userbuild->build_note, 40, '...') }}</p>

                            <div class="build-footer">
                                <div class="action-buttons">
                          <a href="#" class="btn btn-primary btn-sm" id="vieweditbtn" data-bs-toggle="modal" data-bs-target="#userbuildinfoModal" data-build-id="{{ $userbuild->id }}">
                                    View/Edit
                                </a>
                                <form action="{{ route('builds.delete', $userbuild->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>

                                </div>

                            </div>
                        </div>

                      
                    </div>
                </div>
            @endforeach
        @endif

    <!-- Modal for Build Info -->
    <div class="modal fade" id="userbuildinfoModal" tabindex="-1" aria-labelledby="userbuildinfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userbuildinfoModalLabel">Build Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('userbuildinfoModal');
        const modalBody = modal.querySelector('.modal-body');

        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const buildId = button.getAttribute('data-build-id');

            // Load content dynamically for the modal
            fetch(`builds/buildinfo/${buildId}?edit=true`)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                    initializeEditFunctionality();
                })
                .catch(error => console.error('Error loading build info:', error));
        });

        function initializeEditFunctionality() {
            const editButton = document.getElementById('editButton');
            const saveButton = document.getElementById('saveButton');

            if (editButton) {
                editButton.addEventListener('click', function() {
                    // Enable form fields
                    document.getElementById('build_name').readOnly = false;
                    document.getElementById('tag').readOnly = false;
                    document.getElementById('build_note').readOnly = false;
                    document.getElementById('published').disabled = false;
                    
                    // Enable tag checkboxes
                    document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
                        checkbox.disabled = false;
                    });
                    
                    // Show save button and hide edit button
                    editButton.classList.add('d-none');
                    saveButton.classList.remove('d-none');
                });
            }

            // Add event listeners to checkboxes for tag updates
            document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateTags);
            });
        }
        
        function updateTags() {
                const selectedTags = [];
                const selectedTagsContainer = document.getElementById('selected-tags-container');
                selectedTagsContainer.innerHTML = ''; // Clear existing tags

                document.querySelectorAll('#checkbox-container input[type="checkbox"]:checked').forEach(checkbox => {
                    selectedTags.push(checkbox.value);
                    
                    // Define tag colors
                    const tagColors = {
                        'Recommended': 'success',
                        'Editing': 'info',
                        'Office': 'danger',
                        'Gaming': 'primary',
                        'School': 'warning'
                    };

                    // Create badge element
                    const badge = document.createElement('span');
                    const color = tagColors[checkbox.value] || 'secondary';
                    badge.className = `badge bg-${color} text-white me-2`;
                    badge.textContent = checkbox.value;
                    selectedTagsContainer.appendChild(badge);
                });
                
                document.getElementById('tag').value = selectedTags.join(',');
            }

            // Initialize tags on page load
            document.addEventListener('DOMContentLoaded', updateTags);
    });


        </script>
    </x-app-layout>
