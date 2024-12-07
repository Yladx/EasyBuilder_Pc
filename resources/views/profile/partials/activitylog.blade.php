@php
    $activityLogs = Auth::user()->activityLogs()->latest()->take(20)->get();
@endphp

<link rel="stylesheet" href="{{ asset('css/profile/activity-log.css') }}">

<div class="activity-log-container">
    <div class="activity-log-header">
        <h2 class="activity-log-title">Recent Activity</h2>
    </div>

    @if($activityLogs->isEmpty())
        <div class="alert alert-info">
            No recent activity found.
        </div>
    @else
        <ul class="activity-log-list">
            @foreach($activityLogs as $log)
                <li class="activity-log-item">
                    <div class="activity-log-item-header">
                        <span class="activity-log-action">{{ $log->activity }}</span>
                        <span class="activity-log-timestamp">
                            {{ $log->activity_timestamp ? $log->activity_timestamp->diffForHumans() : 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="activity-log-details">
                        <span class="activity-log-type activity-log-type-{{ $log->type }}">
                            {{ $log->type }}
                        </span>
                        {{ $log->activity_details }}
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
