<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
        <div class="text text-white">Manage ADS</div>

        <div class="row mb-4">
            <!-- Total Advertisements -->
            <div class="col-md-4 col-sm-12">
                <div class="custom-card bg-yellow">
                    <div class="icon">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <div class="card-content">
                        <h5>Total Advertisements</h5>
                        <p class="fs-2" id="total-ads">{{ $adStats['total'] ?? 0 }}</p>
                        <span><hr></span>
                    </div>
                </div>
            </div>
            <!-- Published Advertisements -->
            <div class="col-md-4 col-sm-12">
                <div class="custom-card bg-green">
                    <div class="icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="card-content">
                        <h5>Published Advertisements</h5>
                        <p class="fs-2" id="published-ads">{{ $adStats['published'] ?? 0 }}</p>
                        <span>out of {{ $adStats['total'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <!-- Unpublished Advertisements -->
            <div class="col-md-4 col-sm-12">
                <div class="custom-card bg-red">
                    <div class="icon">
                        <i class="fa-solid fa-times-circle"></i>
                    </div>
                    <div class="card-content">
                        <h5>Unpublished Advertisements</h5>
                        <p class="fs-2" id="unpublished-ads">{{ $adStats['unpublished'] ?? 0 }}</p>
                        <span>out of {{ $adStats['total'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mx-5">

        <!-- Advertisement Cards -->
        @if($advertisements->isEmpty())
            <p>No advertisements available.</p>
        @else
            <div class="row">
                @foreach($advertisements as $ad)
                <div id="ad-card-{{ $ad->id }}" class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="media-container position-relative">
                            @if($ad->type === 'image' && $ad->src)
                                <img src="{{ asset('storage/' . $ad->src) }}" class="card-img-top" alt="{{ $ad->label }}" style="height: 200px; object-fit: cover;">
                            @elseif($ad->type === 'video' && $ad->src)
                                <video class="card-img-top" loop controls style="height: 200px; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $ad->src) }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ asset('default_ad_image.jpg') }}" class="card-img-top" alt="Default Advertisement" style="height: 200px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                @php
                                    $words = explode(' ', $ad->label);
                                    $truncatedLabel = count($words) > 5 
                                        ? implode(' ', array_slice($words, 0, 5)) . '...' 
                                        : $ad->label;
                                @endphp
                                {{ $truncatedLabel }}
                            </h5>
                            <p class="card-text border p-1" style="min-height: 75px; max-height: 75px; overflow-y: auto;">{{ $ad->caption }}</p>
                            @if($ad->access_link)
                                <a href="{{ $ad->access_link }}" target="_blank" class="btn btn-link">Learn More</a>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <form action="{{ route('ads.toggle', $ad->id) }}" method="POST" class="toggle-ad-form d-inline">
                                    @csrf
                                    <input type="hidden" name="advertise" value="{{ $ad->advertise ? 0 : 1 }}">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input toggle-switch"
                                            id="toggleAd-{{ $ad->id }}"
                                            {{ $ad->advertise ? 'checked' : '' }}
                                        >
                                        <label
                                            class="form-check-label ms-2"
                                            for="toggleAd-{{ $ad->id }}"
                                        >
                                            {{ $ad->advertise ? 'Advertised' : 'Not Advertised' }}
                                        </label>
                                    </div>
                                </form>

                                <form action="{{ route('ads.destroy', $ad->id) }}" method="POST" class="delete-ad-form" data-id="{{ $ad->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer text-muted">{{ $ad->brand ?? 'No Brand' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- JavaScript -->
        <script>
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



        </script>


    </div>


    <!-- Add Advertisement Button -->

    <div  class="position-fixed bottom-0 end-0 p-3">

        <button type="button" class="btn btn-dark d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#adminModal"  id="addAdsButton">
            <!-- SVG Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" class="me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M19 4h-14a3 3 0 0 0 -3 3v10a3 3 0 0 0 3 3h14a3 3 0 0 0 3 -3v-10a3 3 0 0 0 -3 -3zm-10 4a3 3 0 0 1 2.995 2.824l.005 .176v4a1 1 0 0 1 -1.993 .117l-.007 -.117v-1h-2v1a1 1 0 0 1 -1.993 .117l-.007 -.117v-4a3 3 0 0 1 3 -3zm0 2a1 1 0 0 0 -.993 .883l-.007 .117v1h2v-1a1 1 0 0 0 -1 -1zm8 -2a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -.883 .993l-.117 .007h-1.5a2.5 2.5 0 1 1 .326 -4.979l.174 .029v-2.05a1 1 0 0 1 .883 -.993l.117 -.007zm-1.41 5.008l-.09 -.008a.5 .5 0 0 0 -.09 .992l.09 .008h.5v-.5l-.008 -.09a.5 .5 0 0 0 -.318 -.379l-.084 -.023z" />
            </svg>
            Add Advertisement
        </button>
    </div>

</x-admin-layout>
