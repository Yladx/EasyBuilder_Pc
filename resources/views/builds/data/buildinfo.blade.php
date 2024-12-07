<div class="build-card">
    <!-- Build Image -->
    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
    <img src="{{ asset('storage/' . $buildinfo->image) }}"
     class="card-img"
     alt="{{ $buildinfo->build_name }}">
    </div>

    <!-- Build Information -->
    <div class="build-info">
        <div class="build-header text-black">
            <!-- Build Title -->
            <h3 class="build-title d-inline text-black">{{ $buildinfo->build_name }}</h3>

            <!-- Display Tags as Badges (inline with title) -->
            <span class="build-tags ms-2">
                @foreach(explode(',', $buildinfo->tag) as $tag)
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
            </span>
        </div>
        @if ($buildinfo->user_id)
        <p class="text-muted fs-6 mb-2">
            <i class="fas fa-user me-1"></i>
            by: {{ $buildinfo->user_id === 'Deleted' ? 'Deleted User' : $buildinfo->user->name }}
        </p>
    @endif

            <!-- Average Rating as Stars -->
            @if($averageRating)
                <div class="average-rating mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($averageRating))
                            <span class="star">&#9733;</span> <!-- Filled Star -->
                        @elseif($i - $averageRating < 1)
                            <span class="star half">&#9733;</span> <!-- Half Star -->
                        @else
                            <span class="star empty">&#9733;</span> <!-- Empty Star -->
                        @endif
                    @endfor
                </div>
            @else
                <div class="average-rating">
                    <span class="no-rating">No ratings yet</span>
                </div>
            @endif

            <!-- Build Description -->
            <p class="build-description text-black">{{ $buildinfo->build_note }}</p>
    </div>


    <div class="accordion mt-4" id="buildDetailsAccordion">
        @foreach(['gpu', 'cpu', 'motherboard', 'ram', 'storage', 'powerSupply', 'pcCase'] as $component)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ ucfirst($component) }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($component) }}" aria-expanded="true" aria-controls="collapse{{ ucfirst($component) }}">
                        <strong>{{ ucfirst($component) }}:</strong>
                        @if ($component === 'ram')
                            <div class="ram-list">
                                @if ($rams && $rams->isNotEmpty())
                                    @foreach ($rams as $ram)
                                        <span>{{ $ram->name }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </div>
                        @else
                            {{ $buildinfo->$component->name ?? 'N/A' }}
                        @endif
                    </button>
                </h2>
                <div id="collapse{{ ucfirst($component) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ ucfirst($component) }}" data-bs-parent="#buildDetailsAccordion">
                    <div class="accordion-body">
                        @if ($component === 'ram')
                            @if ($rams && $rams->isNotEmpty())
                                <table class="table table-bordered table-striped component-table">
                                    <tbody>
                                        <!-- Table Header -->


                                        <!-- Table Body -->
                                        @foreach ($rams->first()->getAttributes() as $attr => $value)
                                            @if (!in_array($attr, ['id', 'build_id', 'image', 'created_at', 'updated_at']))
                                                <tr>
                                                    <!-- Attribute Name in First Column -->
                                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $attr)) }}</strong></td>

                                                    <!-- Values for Each RAM -->
                                                    @foreach ($rams as $ram)
                                                        <td>{{ $ram->$attr ?? 'N/A' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>

                                </table>
                            @else
                                <p>No RAM found.</p>
                            @endif
                        @else
                            @if ($buildinfo->$component)
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped component-table">
                                            <tbody>
                                                @foreach ($buildinfo->$component->getFillable() as $attr)
                                                    @if (!in_array($attr, ['name', 'id', 'image']))
                                                        <tr>
                                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $attr)) }}</strong></td>
                                                            <td>{{ $buildinfo->$component->$attr ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Right Column -->

                                </div>
                            @else
                                <p>No {{ ucfirst($component) }} found.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Accessories Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAccessories">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccessories" aria-expanded="true" aria-controls="collapseAccessories">
                    <strong>Accessories:</strong>
                </button>
            </h2>
            <div id="collapseAccessories" class="accordion-collapse collapse" aria-labelledby="headingAccessories" data-bs-parent="#buildDetailsAccordion">
                <div class="accordion-body">
                    <p> {{ $buildinfo->accessories ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(request()->query('admin') === 'true')
    <div class="activity-logs">
        <h5 class="mt-4 mb-3">Activity Logs for this Build:</h5>
        @if($activityLogs->isEmpty())
            <p class="text-muted">No activity logs available for this build.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>User Name</th>
                            <th>Action</th>
                            <th>Activity</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activityLogs as $log)
                            <tr>
                                <td>{{ $log->user->name ?? 'Unknown User' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->action == 'update' ? 'info' : ($log->action == 'delete' ? 'danger' : 'success') }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>{{ $log->activity }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->activity_timestamp)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endif


</div>


<!-- Sticky Bottom Section --><div class="sticky-footer modal-footer bg-light">

    <!-- Rate Now Section -->
    @if(request()->query('admin') === 'true')
    {{-- Check if the logged-in user is an admin --}}
    <p class="text-dark">Admins do not need to rate builds.</p>
@elseif(auth()->check() && session('role') === 'user')
    {{-- Check if the logged-in non-admin user is allowed to rate --}}
    @if($buildinfo->user_id == auth()->id())
      
        {{-- If the user owns the build, do not allow rating --}}
        <p class="text-dark">You cannot rate your own build.</p>
    @elseif($userHasRated)
        <p class="text-dark">You already rated this build: {{ $userRating }} stars</p>
    @else
        <!-- Button to trigger collapse -->
        <button id="rateNowBtn" class="btn btn-dark mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#ratingForm" aria-expanded="false" aria-controls="ratingForm">
            Rate Now
        </button>

        <!-- Collapsible Rating Form -->
        <div class="collapse mt-3" id="ratingForm">
            <form method="POST" action="{{ route('rate.build') }}" class="rating-form">
                @csrf
                <input type="hidden" name="build_id" value="{{ $buildinfo->id }}">
                
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Left side: Rate text and stars -->
                    <div class="d-flex align-items-center gap-3">
                        <p class="card-title text-dark mb-0"><strong>Rate:</strong></p>
                        <div class="star-rating mb-0">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" />
                                <label for="star{{ $i }}" title="{{ $i }} stars">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Right side: Submit button -->
                    <div>
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
@else
    {{-- Show login prompt if no user is authenticated --}}
    <p><a href="{{ route('login') }}" class="btn btn-warning">Login to rate this build</a></p>
@endif

</div>

</div>
