document.addEventListener('DOMContentLoaded', () => {
    // Get DOM elements
    const folders = document.querySelectorAll('.folder');
    const modules = document.querySelectorAll('.module');
    const contextMenu = document.getElementById('customContextMenu');
    const adminModalBody = document.getElementById('adminModalBody');
    const adminModal = new bootstrap.Modal(document.getElementById('adminModal'));
    const adminModalLabel = document.getElementById('adminModalLabel');

    // Function to handle loading content dynamically
    function loadModalContent(url, title) {
        adminModalLabel.textContent = title;

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
                initTinyMCE(); // Initialize TinyMCE editor after loading the form
                initFormSubmission(); // Bind AJAX submission handler
                adminModal.show(); // Show the modal after content is loaded
            })
            .catch(error => {
                console.error('Error loading content:', error);
                adminModalBody.innerHTML = '<p class="text-danger">Failed to load content. Please try again later.</p>';
            });
    }

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
        if (contextMenu) {
            contextMenu.style.display = 'none';
        }
    });

    // Show context menu on right-click for modules
    modules.forEach(module => {
        module.addEventListener('contextmenu', (e) => {
            e.preventDefault();

            const mouseX = e.clientX;
            const mouseY = e.clientY;
            const moduleId = module.dataset.id;
            const moduleTitle = module.querySelector('.module-title')?.innerText;

            // Set context menu attributes
            contextMenu.setAttribute('data-id', moduleId);
            contextMenu.setAttribute('data-title', moduleTitle);

            // Position context menu
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
    if (contextMenu) {
        contextMenu.addEventListener('click', (e) => {
            const target = e.target;
            const moduleId = contextMenu.getAttribute('data-id');

            if (target.id === 'viewEditOption') {
                loadModalContent(`${moduleRoutes.edit}${moduleId}/edit`, 'Edit Module');
            }

            if (target.id === 'deleteOption') {
                deleteModule(moduleId);
            }

            contextMenu.style.display = 'none';
        });
    }

    // Function to delete module
    function deleteModule(moduleId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this module?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${moduleRoutes.delete}${moduleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remove the module from the DOM
                        document.querySelector(`.module[data-id="${moduleId}"]`)?.remove();
                    } else {
                        throw new Error(data.message || 'Failed to delete module');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                });
            }
        });
    }

    // Event handler for Add Module button
    const addModuleButton = document.getElementById('addModuleButton');
    if (addModuleButton) {
        addModuleButton.addEventListener('click', () => {
            loadModalContent(moduleRoutes.create, 'Add Module');
        });
    }

    // Initialize TinyMCE
    function initTinyMCE() {
        if (tinymce.get('information')) {
            tinymce.get('information').remove();
        }

        tinymce.init({
            selector: '#information',
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount hr',
            toolbar: 'formatselect | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr | code',
            height: 300,
            menubar: false,
            branding: false,
            block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
            content_style: "body { font-family:Arial,Helvetica,sans-serif; font-size:14px }"
        });
    }

    // Handle form submission
    function initFormSubmission() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        adminModal.hide();
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to submit form');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'An unexpected error occurred.',
                    icon: 'error'
                });
            });
        });
    }

    // Event handler for module click to open modal for editing
    modules.forEach(module => {
        module.addEventListener('click', () => {
            const moduleId = module.dataset.id;
            loadModalContent(`${moduleRoutes.edit}${moduleId}/edit`, 'Edit Module');
        });
    });
});
