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

    <script src="{{ asset('js/admin/manage-ads.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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


</x-admin-layout>

    