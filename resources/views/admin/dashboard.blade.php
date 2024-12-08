<x-admin-layout>

    <!-- Dashboard Content -->
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
<!-- Green Card -->
> <div class="text-center mb-4">
    <h3 class="fw-bold text-white">Welcome to Admin Dashboard</h3>
</div>
    <div class="row">
        
        <div class="col-md-9 col-xs-12"> 
           <!-- 6x6 Grid for Session Counts -->
           <div class="row mt-4">
            <!-- Guest Sessions -->
            <div class="col-md-6">
                <div class="custom-card bg-red">
                    <div class="icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="card-content">
                        <h5>Guest Sessions</h5>
                        <p class="fs-2 guest-sessions-count">{{ $guestSessionsCount }}</p>

                    </div>
                </div>
            </div>

            <!-- User Sessions -->
            <div class="col-md-6">
                <div class="custom-card bg-blue">
                    <div class="icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="card-content">
                        <h5>User Sessions<s</h5>
                        <p class="fs-2 user-sessions-count">{{ $userSessionsCount }}</p>

                    </div>
                </div>
            </div>

        </div>


        <!-- Activity Log Chart -->
        <div class="mt-4">
            <div class="custom-card bg-green chart-container" style="position: relative; height:50vh; width:100%;">
                <canvas id="activityLogChart"></canvas>
            </div>
          
        </div>
    </div>

        <div class="col-md-3 col-xs-12 mt-1">
            @include('admin.content.partials.recentactivity')
        </div>

    </div>
    </div>
   

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Data from the controller
            const activityData = @json($activityData);

            // Prepare labels and data for the chart
            const labels = activityData.map(entry => entry.date);
            const activityCounts = activityData.map(entry => entry.count);

            // Chart configuration
            const config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'User Activities',
                        data: activityCounts,
                        borderColor: 'white',
                        backgroundColor: 'rgba(0, 0, 0, 0.2)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'top',
                            labels: {
                                color: 'white',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: { 
                            enabled: true,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            title: { 
                                display: true, 
                                text: 'Date',
                                color: 'white'
                            },
                            grid: { 
                                display: false 
                            },
                            ticks: {
                                color: 'white',
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            title: { 
                                display: true, 
                                text: 'Number of Activities',
                                color: 'white'
                            },
                            beginAtZero: true,
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1000
                    }
                }
            };

            // Create the chart
            const ctx = document.getElementById('activityLogChart').getContext('2d');
            const chart = new Chart(ctx, config);

            // Handle resize
            window.addEventListener('resize', function() {
                chart.resize();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateSessionCounts() {
                fetch('/admin/dashboard/session-counts')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const sessionCounts = data.sessionCounts;
                            document.querySelector('.guest-sessions-count').textContent = sessionCounts.guest_sessions;
                            document.querySelector('.user-sessions-count').textContent = sessionCounts.user_sessions;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching session counts:', error);
                    });
            }

            // Initial update
            updateSessionCounts();

            // Periodic updates every 30 seconds
            setInterval(updateSessionCounts, 2000);
        });
    </script>

    <style>
        .chart-container {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 40vh !important;
            }
        }

        @media (max-width: 576px) {
            .chart-container {
                height: 30vh !important;
            }
        }
    </style>

</x-admin-layout>
