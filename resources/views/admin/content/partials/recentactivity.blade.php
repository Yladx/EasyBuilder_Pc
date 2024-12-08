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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateRecentActivities() {
            fetch('/admin/dashboard/recent-activities')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const recentActivities = data.recentActivities;
                        const logsContainer = document.getElementById('logsContainer');
                        logsContainer.innerHTML = ''; // Clear existing logs

                        // Group activities by date
                        const groupedActivities = groupActivitiesByDate(recentActivities);

                        // Create and append log entries for each date group
                        Object.keys(groupedActivities).forEach(date => {
                            // Add date header
                            const dateHeader = document.createElement('tr');
                            dateHeader.innerHTML = `
                                <td colspan="2" class="date-header">
                                    <div class="date-divider">
                                        <span>${date}</span>
                                    </div>
                                </td>
                            `;
                            logsContainer.appendChild(dateHeader);

                            // Add activities for this date
                            groupedActivities[date].forEach(log => {
                                const logEntry = createLogEntry(log);
                                logsContainer.appendChild(logEntry);
                            });
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function groupActivitiesByDate(activities) {
            const groups = {};
            activities.forEach(activity => {
                const date = new Date(activity.activity_timestamp).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                if (!groups[date]) {
                    groups[date] = [];
                }
                groups[date].push(activity);
            });
            return groups;
        }

        function getIconClass(action) {
            switch(action.toLowerCase()) {
                case 'login':
                    return 'fa-sign-in-alt';
                case 'logout':
                    return 'fa-sign-out-alt';
                case 'create':
                    return 'fa-plus';
                case 'update':
                    return 'fa-edit';
                case 'delete':
                    return 'fa-trash';
                case 'view':
                    return 'fa-eye';
                default:
                    return 'fa-circle';
            }
        }

        function getIconBackground(action) {
            switch(action.toLowerCase()) {
                case 'login':
                    return 'login';
                case 'logout':
                    return 'logout';
                case 'create':
                    return 'create';
                case 'update':
                    return 'update';
                case 'delete':
                    return 'delete';
                case 'view':
                    return 'view';
                default:
                    return 'default';
            }
        }

        function createLogEntry(log) {
            const logEntry = document.createElement('tr');
            const iconClass = getIconClass(log.action);
            const iconBackground = getIconBackground(log.action);
            const time = new Date(log.activity_timestamp).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            logEntry.classList.add('align-middle');
            logEntry.innerHTML = `
                <td>
                    <div class="activity-icon ${iconBackground}">
                        <i class="fa ${iconClass}"></i>
                    </div>
                </td>
                <td>
                    <div class="activity-content">
                        <p class="activity-title">${log.activity}</p>
                        <p class="activity-details">${log.activity_details}</p>
                        <small class="activity-time">${time}</small>
                    </div>
                </td>
            `;
            return logEntry;
        }

        // Initial load
        updateRecentActivities();

        // Refresh every 30 seconds
        setInterval(updateRecentActivities, 30000);
    });
</script>

<style>
.activity-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 10px;
}

.activity-icon i {
    font-size: 0.9rem;
    color: white;
}

.activity-content {
    padding: 5px 0;
}

.activity-title {
    margin: 0;
    color: #e9ecef;
    font-weight: 500;
    font-size: 14px;
}

.activity-details {
    margin: 2px 0;
    color: #6c757d;
    font-size: 12px;
}

.activity-time {
    color: #0dcaf0;
    font-size: 11px;
    font-weight: 500;
    background: rgba(13, 202, 240, 0.1);
    padding: 2px 8px;
    border-radius: 12px;
    display: inline-block;
    margin-top: 4px;
}

.date-header {

    padding: 15px 0 5px 0 !important;
}

.date-divider {
    position: relative;
    text-align: center;
    color: #6c757d;
    font-size: 12px;
    font-weight: 500;
    padding: 5px 0;
}

.date-divider span {
    background: #1a1a1a;
    padding: 0 10px;
    position: relative;
    z-index: 1;
}

.date-divider:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #2a2a2a;
}

/* Login - Blue gradient */
.activity-icon.login {
    background: linear-gradient(45deg, #0f1924, #2a5378);
}

/* Logout - Red gradient */
.activity-icon.logout {
    background: linear-gradient(45deg, #1a1a1a, #8B0000);
}

/* Create - Green gradient */
.activity-icon.create {
    background: linear-gradient(45deg, #0f1924, #006400);
}

/* Update - Orange gradient */
.activity-icon.update {
    background: linear-gradient(45deg, #1a1a1a, #CD853F);
}

/* Delete - Red gradient */
.activity-icon.delete {
    background: linear-gradient(45deg, #1a1a1a, #8B0000);
}

/* View - Purple gradient */
.activity-icon.view {
    background: linear-gradient(45deg, #1a1a1a, #483D8B);
}

/* Default - Gray gradient */
.activity-icon.default {
    background: linear-gradient(45deg, #1a1a1a, #4f4f4f);
}

.activity-header {
    color: #f8f9fa;
    z-index: 10;
    padding: 10px;
    background: linear-gradient(180deg, #0d0d0d, #1a1a1a, #2d1810);
;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.logs-container {
    box-sizing: border-box;
    max-height: 440px;
    
    overflow-y: auto;
    border-radius: 20px 0 0 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Webkit (Chrome, Edge, Safari) Scrollbar Styles */
.logs-container::-webkit-scrollbar {
    width: 2px; /* Width of the scrollbar */
    height: 2px; /* Optional: height for horizontal scrollbar */
}

.logs-container::-webkit-scrollbar-track {
    background: #fafafa27; /* Scrollbar track background */
    border-radius: 5px; /* Rounded edges for the track */
}

.logs-container::-webkit-scrollbar-thumb {
    background: #888; /* Scrollbar thumb color */


}

.logs-container::-webkit-scrollbar-thumb:hover {
    background: #555; /* Darker color when hovering over the thumb */
}

.logs-container::-webkit-scrollbar-corner {
    background: #262440; /* Corner color (if both vertical and horizontal scrollbars are present) */
}

/* Table Styling */
.table {
    color: #adb5bd;
}

.table th {
    color: #f8f9fa;
    font-weight: bold;
    font-size: 14px;
}

.table td {
    font-size: 14px;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2) !important;
}

#viewAllActivities {
    background: linear-gradient(45deg, #0f1924, #2a5378);
    border: none;
    color: white;
    font-weight: 500;
    letter-spacing: 0.5px;
}

#viewAllActivities:hover {
    background: linear-gradient(45deg, #162736, #3a6ea3);
    box-shadow: 0 4px 12px rgba(42, 83, 120, 0.3) !important;
}
</style>
