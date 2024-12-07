
document.addEventListener('DOMContentLoaded', () => {
    const toggleForms = document.querySelectorAll('.toggle-ad-form');
    const deleteForms = document.querySelectorAll('form.delete-ad-form');
    const adminModal = document.getElementById('adminModal');
    const adminModalBody = document.getElementById('adminModalBody');

    function refreshStatistics() {
        fetch('{{ route("ads.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch statistics.');
            return response.json();
        })
        .then(data => {
            // Update statistics with smooth animation
            animateValue('total-ads', parseInt(document.getElementById('total-ads').textContent), data.adStats.total, 500);
            animateValue('published-ads', parseInt(document.getElementById('published-ads').textContent), data.adStats.published, 500);
            animateValue('unpublished-ads', parseInt(document.getElementById('unpublished-ads').textContent), data.adStats.unpublished, 500);
        })
        .catch(error => {
            console.error('Error refreshing statistics:', error);
        });
    }

    // Function to animate number changes
    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        if (start === end) return;
        const range = end - start;
        const increment = end > start ? 1 : -1;
        const stepTime = Math.abs(Math.floor(duration / range));
        let current = start;
        const timer = setInterval(() => {
            current += increment;
            obj.textContent = current;
            if (current === end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    // Handle Advertisement Toggle
    toggleForms.forEach((form) => {
        form.addEventListener('change', async (event) => {
            event.preventDefault();

            const checkbox = form.querySelector('input[type="checkbox"]');
            const label = form.querySelector('label');
            const advertiseValue = checkbox.checked ? 1 : 0;
            const url = form.action;

            // Add loading state
            checkbox.disabled = true;
            label.style.opacity = '0.5';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ advertise: advertiseValue }),
                });

                const data = await response.json();

                if (data.success) {
                    // Update label with fade transition
                    label.style.transition = 'opacity 0.3s ease';
                    label.style.opacity = '0';
                    setTimeout(() => {
                        label.textContent = advertiseValue ? 'Advertised' : 'Not Advertised';
                        label.style.opacity = '1';
                    }, 300);

                    refreshStatistics();
                } else {
                    checkbox.checked = !checkbox.checked;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update advertisement status.',
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                checkbox.checked = !checkbox.checked;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                });
            } finally {
                checkbox.disabled = false;
                label.style.opacity = '1';
            }
        });
    });

    // Handle Advertisement Deletion
    deleteForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    const adId = form.getAttribute('data-id');
                    const url = form.getAttribute('action');
                    const token = form.querySelector('input[name="_token"]').value;

                    // Add loading state to the delete button
                    const deleteButton = form.querySelector('button');
                    deleteButton.disabled = true;
                    deleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const adCard = document.getElementById(`ad-card-${adId}`);
                            if (adCard) {
                                // Add fade-out animation
                                adCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                                adCard.style.opacity = '0';
                                adCard.style.transform = 'scale(0.9)';
                                setTimeout(() => {
                                    adCard.remove();
                                }, 500);
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                            });

                            refreshStatistics();
                        } else {
                            throw new Error(data.message || 'Failed to delete the advertisement.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'An unexpected error occurred while deleting.',
                        });
                    })
                    .finally(() => {
                        // Reset button state
                        deleteButton.disabled = false;
                        deleteButton.textContent = 'Delete';
                    });
                }
            });
        });
    });

    // Handle Admin Modal Content Loading
    document.getElementById('addAdsButton').addEventListener('click', () => {
        adminModalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p class="mt-2">Loading...</p></div>';
        adminModalLabel.innerText = 'Add Advertisement';

        fetch('{{ route("admin.getAddAdsForm") }}')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load the form.');
                return response.text();
            })
            .then(html => {
                // Fade in the new content
                adminModalBody.style.opacity = '0';
                adminModalBody.innerHTML = html;
                setTimeout(() => {
                    adminModalBody.style.transition = 'opacity 0.3s ease';
                    adminModalBody.style.opacity = '1';
                }, 50);
            })
            .catch(error => {
                console.error('Error:', error);
                adminModalBody.innerHTML = '<div class="alert alert-danger">Failed to load the form. Please try again later.</div>';
            });
    });
       
});
