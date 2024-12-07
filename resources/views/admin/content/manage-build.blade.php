<x-admin-layout>

    <div class="container-fluid px-md-5 px-xs-2 py-md-3">

        <div class="text-white mb-3">
            <h4>Manage Builds</h4>
        </div>

        <!-- Main Content Row -->
        <div class="row g-3 mt-4">
            <div class="col-lg-4 col-md-12">
                <div class="container">
                    <div class="row mt-xs-4">
                        <!-- Total Builds Card -->
                        <div class="custom-card bg-yellow">
                            <div class="icon">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="card-content">
                                <h5>Total Builds</h5>
                                <p  class="fs-2">{{ $statistics['totalBuilds'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- User Builds Card -->
                        <div class="custom-card bg-blue">
                            <div class="icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="card-content">
                                <h5>User Builds</h5>
                                <p  class="fs-2">{{ $statistics['userBuildsCount'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Recommended Builds Card -->
                        <div class="custom-card bg-green">
                            <div class="icon">
                                <i class="fa-solid fa-thumbs-up"></i>
                            </div>
                            <div class="card-content">
                                <h5>Recommended Builds</h5>
                                <p>{{ $statistics['recommendedBuildsCount'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Builds Table Column -->
            <div class="col-lg-8 col-md-12">
                <div class="  text-white">

                    <div class="card-body p-0">
                        @include('admin.content.partials.build-table')
                    </div>
                </div>
            </div>

            <!-- Statistics Column -->

        </div>
    </div>



<script>
$(document).ready(function() {
    // Initialize DataTable for Recommended Builds
    const recommendedBuildsTable = $('#recommended-builds-table').DataTable({
        dom: 't',
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false
    });

    // Link detached controls for recommended builds
    $('#recommended-section .form-select').first().on('change', function() {
        recommendedBuildsTable.page.len($(this).val()).draw();
        updatePagination(recommendedBuildsTable, '#recommended-table-pagination');
    });

    $('#recommended-section input[type="search"]').on('keyup', function() {
        recommendedBuildsTable.search(this.value).draw();
        updatePagination(recommendedBuildsTable, '#recommended-table-pagination');
    });

    $('#recommended-section .form-select').eq(1).on('change', function() {
        const value = $(this).val();
        recommendedBuildsTable
            .column(4)
            .search(value === 'archived' ? 'Archived' : (value === 'published' ? 'Published' : ''))
            .draw();
        updatePagination(recommendedBuildsTable, '#recommended-table-pagination');
    });

    // Initialize DataTable for User Builds
    const userBuildsTable = $('#user-build-table').DataTable({
        dom: 't',
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false
    });

    // Link detached controls for user builds
    $('#userbuild-section .form-select').first().on('change', function() {
        userBuildsTable.page.len($(this).val()).draw();
        updatePagination(userBuildsTable, '#user-table-pagination');
    });

    $('#userbuild-section input[type="search"]').on('keyup', function() {
        userBuildsTable.search(this.value).draw();
        updatePagination(userBuildsTable, '#user-table-pagination');
    });

    $('#userbuild-section .form-select').eq(1).on('change', function() {
        const value = $(this).val();
        
        // Clear all filters first
        userBuildsTable
            .columns([3, 4]) // Clear both user and status columns
            .search('')
            .draw();

        if (value === 'deleted') {
            userBuildsTable
                .column(4) // User column
                .search('Deleted User', true, false, true);
        } else if (value === 'published') {
            userBuildsTable
                .column(5) // Status column
                .search('Published', true, false, true);
        } else if (value === 'archived') {
            userBuildsTable
                .column(5) // Status column
                .search('Archived', true, false, true);
        }

        userBuildsTable.draw();
        updatePagination(userBuildsTable, '#user-table-pagination');
    });

    // Update info text on draw
    recommendedBuildsTable.on('draw', function() {
        const info = recommendedBuildsTable.page.info();
        $('#recommended-table-info').html(
            `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal} entries`
        );
        updatePagination(recommendedBuildsTable, '#recommended-table-pagination');
    });

    userBuildsTable.on('draw', function() {
        const info = userBuildsTable.page.info();
        $('#user-table-info').html(
            `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal} entries`
        );
        updatePagination(userBuildsTable, '#user-table-pagination');
    });

    // Initialize pagination for both tables
    updatePagination(recommendedBuildsTable, '#recommended-table-pagination');
    updatePagination(userBuildsTable, '#user-table-pagination');

    // Handle pagination clicks for recommended builds
    $(document).on('click', '#recommended-table-pagination .page-link', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        handlePaginationClick(recommendedBuildsTable, action);
    });

    // Handle pagination clicks for user builds
    $(document).on('click', '#user-table-pagination .page-link', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        handlePaginationClick(userBuildsTable, action);
    });
});

// Function to update pagination
function updatePagination(table, paginationId) {
    const info = table.page.info();
    const $pagination = $(paginationId);
    $pagination.empty();

    // Previous button
    $pagination.append(`
        <li class="page-item ${info.page <= 0 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-action="previous">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
    `);

    // Page numbers
    const startPage = Math.max(0, info.page - 2);
    const endPage = Math.min(info.pages - 1, info.page + 2);

    if (startPage > 0) {
        $pagination.append(`
            <li class="page-item">
                <a class="page-link" href="#" data-action="page-0">1</a>
            </li>
            ${startPage > 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
        `);
    }

    for (let i = startPage; i <= endPage; i++) {
        $pagination.append(`
            <li class="page-item ${i === info.page ? 'active' : ''}">
                <a class="page-link" href="#" data-action="page-${i}">${i + 1}</a>
            </li>
        `);
    }

    if (endPage < info.pages - 1) {
        $pagination.append(`
            ${endPage < info.pages - 2 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
            <li class="page-item">
                <a class="page-link" href="#" data-action="page-${info.pages - 1}">${info.pages}</a>
            </li>
        `);
    }

    // Next button
    $pagination.append(`
        <li class="page-item ${info.page >= info.pages - 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-action="next">
                <i class="fas fa-angle-right"></i>
            </a>
        </li>
    `);
}

// Function to handle pagination clicks
function handlePaginationClick(table, action) {
    if (action === 'previous') {
        table.page('previous').draw('page');
    } else if (action === 'next') {
        table.page('next').draw('page');
    } else if (action.startsWith('page-')) {
        const pageNum = parseInt(action.split('-')[1]);
        table.page(pageNum).draw('page');
    }
}
</script>




</x-admin-layout>
