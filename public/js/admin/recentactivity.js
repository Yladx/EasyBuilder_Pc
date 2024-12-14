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