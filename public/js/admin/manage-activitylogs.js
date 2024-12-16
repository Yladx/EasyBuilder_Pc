$(document).ready(function() {
    // Toggle filter section
    $('#toggle_filter').click(function() {
        $('.filter-section').toggle();
    });

    let table = $('#activityLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: activityLogsDataUrl,
            data: function(d) {
                d.action = $('#action_filter').val();
                d.type = $('#type_filter').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'activity_timestamp', name: 'activity_timestamp' },
            { data: 'user_id', name: 'user_id' },
            { 
                data: 'action', 
                name: 'action',
                render: function(data, type, row) {
                    let badgeClass = '';
                    switch (data) {
                        case 'login': badgeClass = 'bg-success'; break;
                        case 'logout':
                        case 'delete': badgeClass = 'bg-danger'; break;
                        case 'view': badgeClass = 'bg-primary'; break;
                        case 'request': badgeClass = 'bg-purple'; break;
                        case 'create':
                        case 'rate': badgeClass = 'bg-warning'; break;
                        case 'update': badgeClass = 'bg-info'; break;
                        case 'verify': badgeClass = 'bg-success'; break;
                        default: badgeClass = 'bg-primary';
                    }
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            { data: 'type', name: 'type' },
            { data: 'activity', name: 'activity' },
            { data: 'activity_details', name: 'activity_details' }
        ],
        dom: '<"row"<"col-sm-12"tr>>', // Remove search bar and pagination
        order: [[1, 'desc']],
        paging: false, // Disable pagination
        info: false, // Disable info
        lengthChange: false, // Disable length change
        searching: false // Disable searching
    });

    // Filter button click handler
    $('#filter_button').click(function() {
        table.draw();
    });

    // Reset button click handler
    $('#reset_filter').click(function() {
        $('#action_filter').val('');
        $('#type_filter').val('');
        $('#date_from').val('');
        $('#date_to').val('');
        table.draw();
    });

   
    $('#export_pdf').click(function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
    
        // Table headers
        const headers = ["No", "Date & Time", "User", "Action", "Type", "Activity", "Details"];
    
        // Get table data
        const data = [];
        const actionCount = {};  // Object to track counts of actions per date
        table.rows().every(function() {
            const row = this.data();
            const rowData = [
                row.DT_RowIndex,
                row.activity_timestamp,
                row.user_id,
                row.action,
                row.type,
                row.activity,
                row.activity_details
            ];
            data.push(rowData);
    
            // Extract action and date, then update the count for each action on the given date
            const action = row.action;
            const date = row.activity_timestamp.split(' ')[0];  // Get date part only
            const key = `${action} ${date}`;
            if (actionCount[key]) {
                actionCount[key] += 1;
            } else {
                actionCount[key] = 1;
            }
        });
    
        // Set title
        doc.text('Activity Logs Report', 14, 10);
    
        // Add table
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 20,
            margin: { top: 10, left: 10, right: 10 },
            theme: 'grid',
        });
    
        // Chart.js: Create a chart based on action counts per date
        const chartData = [];
        const labels = [];
        const datasets = [];
    
        // Prepare data for chart
        Object.keys(actionCount).forEach((key, idx) => {
            const [action, date] = key.split(' ');
            if (!labels.includes(date)) {
                labels.push(date);
            }
    
            let actionIndex = datasets.findIndex(dataset => dataset.label === action);
            if (actionIndex === -1) {
                actionIndex = datasets.length;
                datasets.push({
                    label: action,
                    data: Array(labels.length).fill(0),
                    borderColor: getRandomColor(),
                    fill: false,
                    tension: 0.1,
                });
            }
    
            const labelIndex = labels.indexOf(date);
            datasets[actionIndex].data[labelIndex] = actionCount[key];
        });
    
        // Create a chart
        const chartCanvas = document.createElement('canvas');
        const ctx = chartCanvas.getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 10 }, // Smaller legend font
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' actions'; // Custom tooltip label
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45, // Prevent overlap of labels
                            minRotation: 30,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Action Count'
                        },
                        ticks: {
                            stepSize: 1,  // Display only whole numbers
                        }
                    }
                }
            }
        });
    
        // Wait for the chart to be rendered before adding it to the PDF
        chart.update(); // Ensure the chart is rendered
    
        // Increase the resolution of the chart by scaling up the canvas
        const scale = 2; // Increase scale for HD quality
        chartCanvas.width = chartCanvas.width * scale;
        chartCanvas.height = chartCanvas.height * scale;
        chartCanvas.style.width = chartCanvas.width / scale + 'px';
        chartCanvas.style.height = chartCanvas.height / scale + 'px';
        chart.update(); // Re-render the chart at the new resolution
    
        // After a small delay, we can get the high-res image
        setTimeout(() => {
            // Convert to high-res PNG image
            const chartImage = chartCanvas.toDataURL('image/png', 1.0);  // Quality set to 1 (highest)
    
            // Add the chart to the PDF
            doc.addImage(chartImage, 'PNG', 10, doc.lastAutoTable.finalY + 10, 180, 100);
    
            // Saving the PDF
            doc.save('activity_logs_report_with_high_res_chart.pdf');
        }, 500);  // Adjust the delay as necessary
    });
    
    // Function to generate a random color for the chart lines
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    
    // Export to Excel
    $('#export_excel').click(function() {
        const rows = [];
        const columns = ['No', 'Date & Time', 'User', 'Action', 'Type', 'Activity', 'Details'];

        table.rows({ search: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
            const data = table.row(rowIdx).data();
            rows.push([
                data.DT_RowIndex,
                data.activity_timestamp,
                data.user_id,
                data.action,
                data.type,
                data.activity,
                data.activity_details
            ]);
        });

        const ws = XLSX.utils.aoa_to_sheet([columns, ...rows]);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Activity Logs');
        XLSX.writeFile(wb, 'activity_logs.xlsx');
    });
});

