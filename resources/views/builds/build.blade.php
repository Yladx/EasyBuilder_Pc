<x-app-layout>
    <div class="container mt-4">
        @guest
            <!-- Display builds for guest users -->
            <h3 class="text-start  mb-4">Recommended Builds</h2>
                <hr>
            <div class="row mt-4" id="builds-container">
                @if($builds->isEmpty())
                    <div class="col-12">
                        <p>No builds found for this category.</p>
                    </div>
                @else
                    @foreach($builds as $build)
                        <div class="col-lg-3 col-md-4 col-xs-6 mb-4 build-item"
                             data-name="{{ strtolower($build->build_name) }}"
                             onclick="viewBuildDetails({{ $build->id }})">
                            <div class="card">
                            <img src="{{ asset('storage/' . $build->image) }}" class="card-img-top" alt="{{ $build->build_name }}">
                            <div class="card-body">
                                    <h5 class="card-title">{{ $build->build_name }}</h5>

                                    <!-- Tags as Badges -->
                                    <div class="card-tags">
                    @foreach(explode(',', $build->tag) as $tag)
                        @php
                            // Define specific colors for tags
                            $tagColors = [
                                'Recommended' => 'success', 
                            'School' => 'warning',     
                            'Office' => 'primary',    
                            'Gaming' => 'danger'      
                            ];
                            // Determine the color class, default to 'secondary' (gray) if no match
                            $color = $tagColors[trim($tag)] ?? 'secondary';
                        @endphp
                        <!-- Display tag with dynamic color -->
                        <span class="badge bg-{{ $color }} text-white">{{ trim($tag) }}</span>
                    @endforeach
                </div>

                                    <p class="card-text">{{ $build->build_note }}</p>

                                    <!-- Ratings -->
                                    <div class="card-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($build->average_rating))
                                                <span class="star">&#9733;</span> <!-- Full star -->
                                            @elseif($i - $build->average_rating < 1)
                                                <span class="star" style="background: linear-gradient(90deg, #ffc107 50%, #d3d3d3 50%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">&#9733;</span> <!-- Half star -->
                                            @else
                                                <span class="star empty">&#9733;</span> <!-- Empty star -->
                                            @endif
                                        @endfor
                                        <span>({{ number_format($build->average_rating, 2) }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <!-- Display builds for logged-in users -->


            <nav class="nav mb-4 border-bottom" id="builds-nav">
                <a class="nav-link fw-bolder " href="{{ route('builds.display', '') }}">All</a>
                <a class="nav-link   fw-bolder"  href="{{ route('builds.display', 'recommended') }}">Recommended</a>
                <a class="nav-link  fw-bolder" href="{{ route('builds.display', 'gaming') }}">Gaming</a>
                <a class="nav-link  fw-bolder" href="{{ route('builds.display', 'school') }}">School</a>
                <a class="nav-link  fw-bolder" href="{{ route('builds.display', 'office') }}">Office</a>

            </nav>
    
     
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <div class="build-tags">
                        @if($tag)
                            <h2 class="text-dark">{{ ucfirst($tag) }} Builds</h2>
                        @else
                            <h2 class="text-dark">All Builds</h2>
                        @endif
                        <!-- Additional dynamic tags can be added here -->
                    </div>
                </div>
                <div class="col-md-8 d-flex justify-content-end align-items-center">
                    <div class="me-3">
                        <form method="GET" action="{{ route('builds.display', ['tag' => $tag ?? '']) }}" id="sortForm">
                            <label for="sort" class="me-2">Sort by:</label>
                            <select name="sort" id="sort" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                <option value="latest" {{ ($sort ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="rating" {{ ($sort ?? '') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                <option value="name" {{ ($sort ?? '') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                            @if($search ?? false)
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                        </form>
                    </div>
                    <div>
                        <form method="GET" action="{{ route('builds.display', ['tag' => $tag ?? '']) }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search builds..." value="{{ $search ?? '' }}" />
                            <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Search</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Builds Container -->
            <div class="row mt-4 p-4" id="builds-container">
                @if($builds->isEmpty())
                    <div class="col-12">
                        <p>No builds found for this category.</p>
                    </div>
                @else
                    @foreach($builds as $build)
                        <div class="col-lg-4 col-md-6 col-xs-6 mb-4 build-item" data-name="{{ strtolower($build->build_name) }}" onclick="viewBuildDetails({{ $build->id }})">
                            <div class="card">
                            <img src="{{ asset('storage/' . $build->image) }}" class="card-img-top" alt="{{ $build->build_name }}">
                            <div class="card-body">
                                    <h5 class="card-title">{{ $build->build_name }}</h5>

                                    <!-- Tags as Badges -->
                                    <div class="card-tags">
                                            @foreach(explode(',', $build->tag) as $tag)
                                                @php
                                                    // Define specific colors for tags
                                                    $tagColors = [
                                                        'Recommended' => 'success', 
                                                        'School' => 'warning',     
                                                        'Office' => 'primary',    
                                                        'Gaming' => 'danger'      
                                                    ];
                                                    // Determine the color class, default to 'secondary' (gray) if no match
                                                    $color = $tagColors[trim($tag)] ?? 'secondary';
                                                @endphp
                                                <!-- Display tag with dynamic color -->
                                                <span class="badge bg-{{ $color }} text-white">{{ trim($tag) }}</span>
                                            @endforeach
                                        </div>

                                    <!-- Build Notes -->
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($build->build_note, 40, '...') }}</p>

                                    <!-- Rating -->
                                    <div class="card-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($build->average_rating))
                                                <span class="star">&#9733;</span> <!-- Full star -->
                                            @elseif($i - $build->average_rating < 1)
                                                <span class="star" style="background: linear-gradient(90deg, #ffc107 50%, #d3d3d3 50%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">&#9733;</span> <!-- Half star -->
                                            @else
                                                <span class="star empty">&#9733;</span> <!-- Empty star -->
                                            @endif
                                        @endfor
                                        <span>({{ number_format($build->average_rating, 2) }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    @endforeach
                 <!-- Pagination Links -->
                    <div class="pagination-container my-4 ">
                        {{ $builds->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
                <a href="{{ route('manage.build') }}" class="easypc-btn btn-md  shadow-lg ">
                    <i class="fas fa-tools me-2"></i> Manage Your Builds
                </a>
            </div>


        @endguest
    </div>

    <script>
    function viewBuildDetails(buildId) {
        const modal = new bootstrap.Modal(document.getElementById('buildDetailsModal'));
        const modalBody = document.querySelector('#buildDetailsModal .modal-body');
        const buildInfoRoute = document.getElementById('buildDetailsModal').dataset.route.replace(':id', buildId);

        fetch(buildInfoRoute, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then((response) => response.text())
        .then((data) => {
            modalBody.innerHTML = data;
            modal.show();
            
            // Initialize rate button after modal content is loaded
            initRateButton();
        })
        .catch((error) => console.error('Error fetching build info:', error));
    }

    function initRateButton() {
        const rateButton = document.getElementById('rateNowBtn');
        const ratingForm = document.getElementById('ratingForm');

        if (rateButton && ratingForm) {
            // Toggle button on click
            rateButton.addEventListener('click', function() {
                if (this.getAttribute('aria-expanded') === 'false') {
                    this.innerHTML = '<i class="fas fa-times"></i>';
                    this.classList.add('active');
                } else {
                    this.innerHTML = 'Rate Now';
                    this.classList.remove('active');
                }
            });

            // Handle bootstrap collapse events
            ratingForm.addEventListener('hidden.bs.collapse', function () {
                rateButton.innerHTML = 'Rate Now';
                rateButton.classList.remove('active');
            });

            ratingForm.addEventListener('shown.bs.collapse', function () {
                rateButton.innerHTML = '<i class="fas fa-times"></i>';
                rateButton.classList.add('active');
            });
        }
    }
    </script>

    <style>
        #rateNowBtn.active {
            background: none;
            border: none;
            color: #dc3545;
            padding: 0.375rem 0.5rem;
        }
        
        #rateNowBtn.active:hover {
            color: #bb2d3b;
        }

        #rateNowBtn .fas {
            font-size: 0.875rem;
        }
    </style>
</x-app-layout>
