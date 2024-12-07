document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                const successMessage = tempDiv.querySelector('.alert-success');
                const errorMessage = tempDiv.querySelector('.alert-danger');

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage.textContent.trim()
                    });
                    
                    // Close the modal
                    var contactModal = bootstrap.Modal.getInstance(document.getElementById('contactModal'));
                    contactModal.hide();
                } else if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage.textContent.trim()
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.'
                });
                console.error('Error:', error);
            });
        });
    }
});
