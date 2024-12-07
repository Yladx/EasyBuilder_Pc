document.addEventListener('DOMContentLoaded', function() {
    // Function to update dashboard statistics
    function updateDashboardStats() {
        fetch('/admin/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.stats;

                    // Update total counts
                    document.getElementById('total-users').textContent = stats.total_users;
                    document.getElementById('total-builds').textContent = stats.total_builds;
                    document.getElementById('total-modules').textContent = stats.total_modules;
                    document.getElementById('total-ads').textContent = stats.total_ads;
                    document.getElementById('active-sessions').textContent = stats.active_sessions;

                    // Update sessions by role
                    document.getElementById('guest-sessions').textContent = stats.sessions_by_role.guest;
                    document.getElementById('user-sessions').textContent = stats.sessions_by_role.user;
                    document.getElementById('admin-sessions').textContent = stats.sessions_by_role.admin;

                    // Update recent activities
                    const recentActivitiesList = document.getElementById('recent-activities');
                    recentActivitiesList.innerHTML = ''; // Clear existing activities
                    stats.recent_activities.forEach(activity => {
                        const li = document.createElement('li');
                        li.textContent = `${activity.action} - ${activity.activity} (${activity.activity_timestamp})`;
                        recentActivitiesList.appendChild(li);
                    });

                    // Update activity trend chart (if you have a chart library)
                    updateActivityTrendChart(stats.activity_trend);
                }
            })
            .catch(error => {
                console.error('Error fetching dashboard stats:', error);
            });
    }

    // Function to update activity trend chart (placeholder)
    function updateActivityTrendChart(trendData) {
        // Implement chart update logic here
        // This could use Chart.js or another charting library
        console.log('Activity Trend Data:', trendData);
    }

    // Initial update
    updateDashboardStats();

    // Periodic updates every 30 seconds
    setInterval(updateDashboardStats, 30000);
});
