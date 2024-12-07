<section id="activityLog">
    <div class="container py-4">
        <!-- Activity Log Container -->
        <div class="logs-container">
            <h3 class="sticky-top text-center mb-4 bg-dark" style="color: #f8f9fa; z-index: 10; padding: 10px;">
                Recent Activity Logs
            </h3>

            @if($logs->isEmpty())
                <p class="text-center text-muted">No activity logs available.</p>
            @else
                <table class="table table-borderless text-white mb-0">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Icon</th>
                            <th style="width: 20%;">Timestamp</th>
                            <th style="width: 65%;">Activity and Details</th>
                        </tr>
                    </thead>
                    <tbody id="logsContainer">
                        <!-- Dynamic content will be populated here -->
                    </tbody>
                </table>
            @endif
        </div>
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
                        recentActivities.forEach(log => {
                            // Define icons and colors for actions
                            const actionIcons = {
                                'update': {icon: 'fa-pencil-alt', color: '#FFA500'},
                                'delete': {icon: 'fa-trash', color: '#FF0000'},
                                'view': {icon: 'fa-eye', color: '#1E90FF'},
                                'login': {icon: 'fa-sign-in-alt', color: '#32CD32'},
                                'logout': {icon: 'fa-sign-out-alt', color: '#8B4513'},
                                'request': {icon: 'fa-paper-plane', color: '#FFD700'},
                                'rate': {icon: 'fa-star', color: '#FFFF00'},
                            };

                            const iconClass = actionIcons[log.action]?.icon || 'fa-info-circle';
                            const iconColor = actionIcons[log.action]?.color || '#CCCCCC';

                            const logEntry = document.createElement('tr');
                            logEntry.classList.add('align-middle');
                            logEntry.innerHTML = `
                                <td>
                                    <div class="icon-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; background-color: #0b0b0b; border-radius: 50%;">
                                        <i class="fa ${iconClass}" style="color: ${iconColor}; font-size: 18px;"></i>
                                    </div>
                                </td>
                                <td style="color: #6c757d; font-size: 14px;">${log.activity_timestamp}</td>
                                <td>
                                    <p style="margin: 0; font-weight: bold; color: #adb5bd;">${log.activity}</p>
                                    <i class="mw-2"> 
                                        <p style="
                                            margin: 0; 
                                            color: #6c757d; 
                                            font-size: 10px; 
                                            white-space: nowrap; 
                                            overflow: hidden; 
                                            text-overflow: ellipsis; 
                                            max-width: 200px;
                                        "> 
                                            - ${log.activity_details}
                                        </p>
                                    </i>
                                </td>
                            `;
                            logsContainer.appendChild(logEntry);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching recent activities:', error);
                });
        }

        // Initial update
        updateRecentActivities();

        // Periodic updates every 2 seconds
        setInterval(updateRecentActivities, 2000);
    });
</script>

<style>
/* Container Styling */
.logs-container {
    box-sizing: border-box;
    max-height: 500px;
    
    overflow-y: auto;
    border-radius: 20px;
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

/* Icon Circle */
.icon-circle {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background-color: #343a40;
    border-radius: 50%;
}
</style>
