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
            { 
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { data: 'activity_timestamp', name: 'activity_timestamp' },
            { data: 'user_id', name: 'user_id' },
            { 
                data: 'action',
                name: 'action',
                render: function(data, type, row) {
                    let badgeClass = '';
                    switch(data) {
                        case 'login':
                            badgeClass = 'bg-success';
                            break;
                        case 'logout':
                        case 'delete':
                            badgeClass = 'bg-danger';
                            break;
                        case 'view':
                            badgeClass = 'bg-primary';
                            break;
                        case 'request':
                            badgeClass = 'bg-purple';
                            break;
                        case 'create':
                        case 'rate':
                            badgeClass = 'bg-warning';
                            break;
                        case 'update':
                            badgeClass = 'bg-info';
                            break;
                        case 'verify':
                            badgeClass = 'bg-success';
                            break;
                        default:
                            badgeClass = 'bg-primary';
                    }
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            { data: 'type', name: 'type' },
            { data: 'activity', name: 'activity' },
            { data: 'activity_details', name: 'activity_details' }
        ],
        dom: '<"row"<"col-sm-12"tr>>', // Remove search bar and pagination from DOM
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
});