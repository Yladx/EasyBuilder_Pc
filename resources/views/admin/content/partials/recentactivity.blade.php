@section('head')

<link rel="stylesheet" href="{{ asset('css\admin\recentcactivity.css') }}">
@endsection
<section id="activityLog">


    <div class="container py-4">
        <!-- Activity Log Container -->
        <div class="logs-container">
            <h5 class="sticky-top text-center mb-4 activity-header">
                Recent Activity Logs
            </h5>

            @if($logs->isEmpty())
                <p class="text-center text-muted">No activity logs available.</p>
            @else
                <table class="table table-borderless text-white mb-0">
                   
                    <tbody id="logsContainer">
                        <!-- Dynamic content will be populated here -->
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center mt-1 mb-3">
        <button type="button" class="btn btn-outline-primary px-4 py-2 rounded-pill shadow-sm hover-lift" id="viewAllActivities">
            <i class="fas fa-history me-2"></i>
            View All Activities
        </button>
    </div>

</section>

<script src="{{ asset('/js/admin/recentactivity.js') }}"></script>

