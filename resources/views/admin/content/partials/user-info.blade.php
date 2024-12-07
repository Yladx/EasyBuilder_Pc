<style>
    /* Container Styling */
    .container-fluid {
        max-width: 100%;
        box-sizing: border-box;
        margin: auto;
        position: relative; /* Ensure positioning context for the sticky button */
    }

    /* Form-Like Styling */
    input.form-control {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 0.95rem;
        color: #495057;
    }

    input.form-control[readonly] {
        background-color: #e9ecef; /* Read-only inputs use a slightly darker shade */
        cursor: not-allowed;
    }

    /* Activity Logs Styling */
/* Activity Logs Styling */
.activity-logs-box {
    background-color: #f1f3f5;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    max-height: 520px; /* Default max height */
    overflow-y: auto; /* Enable vertical scroll */
    overflow-x: hidden; /* Prevent horizontal overflow */
}

@media (max-width: 768px) {
    .activity-logs-box {
        max-height: 400px; /* Reduce height for medium screens */
        overflow-y: auto; /* Keep it scrollable */
    }
}




    ul.list-unstyled {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    ul.list-unstyled li {
        padding: 5px 0;
        font-size: 0.9rem;
    }



</style>

<div class="container-fluid p-4 row" style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 100%; margin: auto;">
    {{-- User Information --}}
    <div class="col-lg-5 col-12 mb-4 mb-md-0">

        <h4 class="mb-4 text-center" style="color: #343a40;">User Information</h4>
        <div class="user-info-box p-3 rounded bg-white">
            <!-- Other User Information Fields -->
            <div class="mb-3">
                <label for="userId" class="form-label"><strong>ID</strong></label>
                <input type="text" id="userId" class="form-control" value="{{ $user->id }}" readonly>
            </div>

            <div class="mb-3">
                <label for="buildName" class="form-label"><strong>Build Name</strong></label>
                <input type="text" id="buildName" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="firstName" class="form-label"><strong>First Name</strong></label>
                    <input type="text" id="firstName" class="form-control" value="{{ $user->fname }}" readonly>
                </div>
                <div class="col-6">
                    <label for="lastName" class="form-label"><strong>Last Name</strong></label>
                    <input type="text" id="lastName" class="form-control" value="{{ $user->lname }}" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-9">
                    <label for="email" class="form-label"><strong>Email</strong></label>
                    <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>
                <div class="col-3">
                    <label for="emailVerifiedAt" class="form-label"><strong>Status</strong></label>
                    <input
                        type="text"
                        id="emailVerifiedAt"
                        class="form-control {{ $user->email_verified_at ? 'bg-success text-white' : 'bg-danger text-white' }}"
                        value="{{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}"
                        readonly>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="createdAt" class="form-label"><strong>Created At</strong></label>
                    <input type="text" id="createdAt" class="form-control" value="{{ $user->created_at }}" readonly>
                </div>
                <div class="col-6">
                    <label for="updatedAt" class="form-label"><strong>Updated At</strong></label>
                    <input type="text" id="updatedAt" class="form-control" value="{{ $user->updated_at }}" readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Logs --}}
    @if ($user->activityLogs->isEmpty())
    <p class="text-muted text-center">No activity logs available for this user.</p>
@else
<div class="col-lg-7 col-12">
    <div class="activity-logs-box p-4 rounded bg-light">
        <h5 class="mb-4 text-dark">User Activities</h5>
        
        @php
            // Group logs by date
            $logsByDate = $user->activityLogs->groupBy(function ($log) {
                return $log->created_at->format('Y-m-d');
            })->sortKeysDesc();
        @endphp

        @foreach ($logsByDate as $date => $logs)
            <div class="mb-4">
                <!-- Date Header -->
                <strong class="text-dark d-block mb-3">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</strong>

                <!-- Activity Cards -->
                <div class="row g-3">
                    @foreach ($logs->sortByDesc('created_at') as $log)
                    <div class="col-12">
    <div class="card shadow-sm border-0 p-3 d-flex flex-row justify-content-between align-items-start">
        <div>
            <!-- Timestamp -->
            <p class="text-primary mb-2" style="font-size: 0.9rem;">
                {{ $log->created_at->format('h:i A') }} <!-- Displays time in 12-hour format -->
            </p>
            <!-- Activity -->
            <p class="text-dark mb-1" style="font-size: 1rem;">
                {{ $log->activity }}
            </p>
            <!-- Log Details -->
            <p class="text-muted" style="font-size: 0.9rem;">
                - {{ $log->activity_details }}
            </p>
        </div>
        <!-- Action -->
        <div class="text-end">
            <span class="badge bg-secondary" style="font-size: 0.85rem;">{{ $log->action ?? 'N/A' }}</span>
        </div>
    </div>
</div>

                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

        </div>
    </div>
</div>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="sticky-footer">
    <form id="deleteUserForm" action="{{route('admin.users.delete', $user->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" id="confirmDeleteButton" class="btn btn-danger">Delete User</button>
    </form>
</div>
