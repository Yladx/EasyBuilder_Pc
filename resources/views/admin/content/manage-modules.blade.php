<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">

        <div class="text text-white">Manage Modules</div>


<div class="card bg-secondary text-white text-center ">

    <p class="">Total Modules: {{ $statistics['totalModules'] }}</p>

</div>

<div class="container mt-5">
    <div class="row">
        @foreach($tags as $tag)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-center">
            <div class="folder">
                <div class="scrollable-content p-3">
                    <div class="row g-3 =">
                        @foreach($modules->where('tag', $tag) as $module)
                        <a class="module col-12 col-sm-6 col-md-4 " data-id="{{ $module->id }} "data-bs-toggle="modal" data-bs-target="#adminModal">
                            <div class="module-icon">
                                <i class="fa fa-file" style="font-size:38px;color:rgb(164, 164, 164)"></i>
                            </div>
                            <div class="module-title text-center mt-2 ">{{ $module->title }}</div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
            <p class="mt-2 fw-semibold text-white" >{{ $tag }}</p>
        </div>
        @endforeach
    </div>
</div>



<!-- Custom Context Menu -->
<!-- Context Menu -->
<div id="customContextMenu" class="context-menu">
    <ul>
        <li id="viewEditOption" data-bs-toggle="modal" data-bs-target="#adminModal" >View/Edit</li>
        <li id="deleteOption">Delete</li>
    </ul>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>



<!-- Add Module Button -->
<div  class="position-fixed bottom-0 end-0 p-3">

    <button type="button" class="btn btn-dark d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#adminModal"  id="addModuleButton">
        <!-- SVG Icon -->
        <box-icon name='book-add' type='solid' color='#ffffff' class="me-2"></box-icon>Add Module

    </button>
</div>

  <script>document.addEventListener('DOMContentLoaded', () => {
    const folders = document.querySelectorAll('.folder');
    const modules = document.querySelectorAll('.module');
    const contextMenu = document.getElementById('customContextMenu');
    const adminModalBody = document.getElementById('adminModalBody');
    const adminModal = new bootstrap.Modal(document.getElementById('adminModal'));

    // Handle folder open/close logic
    folders.forEach(folder => {
        folder.addEventListener('click', (e) => {
            const currentFolder = e.currentTarget;

            if (!currentFolder.classList.contains('open')) {
                // Close all other folders
                folders.forEach(f => f.classList.remove('open'));

                // Open the clicked folder
                currentFolder.classList.add('open');
            } else {
                // Toggle close on second click
                currentFolder.classList.remove('open');
            }
        });
    });

    // Close context menu when clicking anywhere else
    document.addEventListener('click', () => {
        contextMenu.style.display = 'none';
    });

    // Show context menu on right-click for modules
    modules.forEach(module => {
        module.addEventListener('contextmenu', (e) => {
            e.preventDefault();

            const mouseX = e.clientX;
            const mouseY = e.clientY;

            const moduleId = module.dataset.id; // Ensure each module has a unique data-id attribute
            const moduleTitle = module.querySelector('.module-title').innerText;

            // Set context menu attributes
            contextMenu.setAttribute('data-id', moduleId);
            contextMenu.setAttribute('data-title', moduleTitle);

            const menuWidth = contextMenu.offsetWidth;
            const menuHeight = contextMenu.offsetHeight;
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            const adjustedX = Math.min(mouseX, viewportWidth - menuWidth - 10);
            const adjustedY = Math.min(mouseY, viewportHeight - menuHeight - 10);

            contextMenu.style.top = `${adjustedY}px`;
            contextMenu.style.left = `${adjustedX}px`;
            contextMenu.style.display = 'block';
        });
    });

    // Handle context menu actions
    contextMenu.addEventListener('click', (e) => {
        const target = e.target;
        const moduleId = contextMenu.getAttribute('data-id');

        if (target.id === 'viewEditOption') {
            loadModalContent(`/admin/modules/${moduleId}/edit`, 'Edit Module');
        }

      if (target.id === 'deleteOption') {
            // SweetAlert for Delete Confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete module?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX DELETE request
                    fetch(`/admin/modules/destroy/${moduleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Remove the module from the DOM
                                document.querySelector(`.module[data-id="${moduleId}"]`).remove();
                            } else {
                                // Show error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        });
                }
            });
        }

        contextMenu.style.display = 'none';
    });

    // Event handler for Add Module button
    document.getElementById('addModuleButton').addEventListener('click', () => {
        loadModalContent('{{ route("modules.create") }}', 'Add Module');
    });

    // Function to handle loading content dynamically
    const loadModalContent = (url, title) => {
        document.getElementById('adminModalLabel').textContent = title;

        // Show loading spinner while fetching the content
        adminModalBody.innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load content.');
                return response.text();
            })
            .then(html => {
                adminModalBody.innerHTML = html;
                initTinyMCE(); // Initialize TinyMCE editor after loading the forma
                initFormSubmission(); // Bind AJAX submission handler
            })
            .catch(error => {
                console.error('Error loading content:', error);
                adminModalBody.innerHTML = '<p class="text-danger">Failed to load content. Please try again later.</p>';
            });
    };

    const initTinyMCE = () => {
    // Check if TinyMCE is already initialized and destroy it
    if (tinymce.get('information')) {
        tinymce.get('information').remove();
    }

    // Initialize TinyMCE editor
    tinymce.init({
        selector: '#information',
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount hr',
        toolbar: ' formatselect | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr | code',
        height: 300,
        menubar: false,
        branding: false,
        block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
        content_style: "body { font-family:Arial,Helvetica,sans-serif; font-size:14px }",
    });



    };

    // AJAX Form Submission
    const initFormSubmission = () => {
        const form = document.querySelector('form');

        form.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                        }).then(() => {
                            adminModal.hide(); // Hide modal
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again later.',
                        icon: 'error',
                    });
                });
        });
    };

    // Event handler for module click to open modal for editing
    modules.forEach(module => {
        module.addEventListener('click', () => {
            const moduleId = module.dataset.id;
            loadModalContent(`/admin/modules/${moduleId}/edit`, 'Edit Module');
        });
    });
});


 </script>
</div>
</div>
</x-admin-layout>
