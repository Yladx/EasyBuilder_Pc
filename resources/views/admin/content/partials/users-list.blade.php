<section id="usersTableSection">
    <div class="row mb-3">
        <!-- Total Users Card -->
        <div class="col-md-4 col-sm-12 ">
            <div class="custom-card bg-blue text-white">
                <div class="icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="card-content">
                    <h5>Total Users</h5>
                    <p id="totalUsers" class="fs-2">{{ $userStats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Verified Users Card -->
        <div class="col-md-4 col-sm-12">
            <div class="custom-card bg-green text-white">
                <div class="icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <h5>Verified Users</h5>
                    <p id="verifiedUsers" class="fs-2">{{ $userStats['verified'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Unverified Users Card -->
        <div class="col-md-4 col-sm-12">
            <div class="custom-card bg-red text-white">
                <div class="icon">
                    <i class="fa-solid fa-exclamation-circle"></i>
                </div>
                <div class="card-content">
                    <h5>Unverified Users</h5>
                    <p id="unverifiedUsers" class="fs-2">{{ $userStats['unverified'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="card-header d-flex justify-content-between align-items-center mb-3 p-1">
        <h5 class="text-white mb-0">Users List</h5>
    </div>
    <section class="tab-content">
        <!-- Users List Section -->
       

        <!-- Detached Controls Section -->
        <div class="controls-section ">
            <!-- Top row - Length and Filter -->
            <div class="d-flex flex-column flex-md-row gap-3 mb-3">
                <div class="d-flex flex-wrap gap-3 flex-grow-1">
                    <!-- Entries length control -->
                    <div class="d-flex align-items-center">
                        <label class="text-white me-2">Show</label>
                        <select class="form-select form-select-sm" style="width: 80px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="text-white ms-2">entries</label>
                    </div>
                    <!-- Filter control -->
                    <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm" id="verificationFilter" style="width: 150px;">
                            <option value="">All Users</option>
                            <option value="verified">Verified Users</option>
                            <option value="unverified">Unverified Users</option>
                        </select>
                    </div>
                </div>
                <!-- Search control -->
                <div class="search-wrapper ms-md-auto">
                    <input type="search" class="form-control form-control-sm w-100" 
                           placeholder="Search users..." 
                           style="min-width: 200px; max-width: 300px;">
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="maintable table-responsive"  style="max-height: 250px; overflow-y: auto;">
            <table class="table table-dark table-striped table-hover" id="userDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                  
                        <th>Email</th>
                        <th>Email Verified At</th>
                        <th>Created At</th>
                        <th>Builds [Total / Published / Unpublished]</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach ($users as $user)
                        <tr onclick="showUserInfo({{ $user->id }})" style="cursor: pointer;">
                            <td data-label="ID">{{ $user->id }}</td>
                            <td data-label="Username">{{ $user->name }}</td>
                            <td data-label="First Name">{{ $user->fname }}</td>
                            <td data-label="Last Name">{{ $user->lname }}</td>
                      
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Email Verified At">{{ $user->email_verified_at ?? 'Not verified' }}</td>
                            <td data-label="Created At">{{ $user->created_at }}</td>
                            <td class="text-center" data-label="Builds">
                                <span class="badge bg-primary">{{ $user->total_builds ?? 0 }}</span> / 
                                <span class="badge bg-success">{{ $user->published_builds ?? 0 }}</span> / 
                                <span class="badge bg-danger">{{ $user->unpublished_builds ?? 0 }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bottom Info and Pagination -->
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center  mt-3">
            <div class="text-white order-2 order-md-1 text-center text-md-start" id="table-info"></div>
            <nav aria-label="Table navigation" class="order-1 order-md-2 d-flex justify-content-center">
                <ul class="pagination pagination-sm mb-0" id="users-table-pagination"></ul>
            </nav>
        </div>
    </section>

</section>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    const usersTable = $('#userDataTable').DataTable({
        dom: 't',
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        lengthChange: true,
        autoWidth: false
    });

    // Link detached controls
    $('.form-select').first().on('change', function() {
        usersTable.page.len($(this).val()).draw();
        updatePagination(usersTable, '#users-table-pagination');
    });

    $('input[type="search"]').on('keyup', function() {
        usersTable.search(this.value).draw();
        updatePagination(usersTable, '#users-table-pagination');
    });

    // Verification filter
    $('#verificationFilter').on('change', function() {
        const value = $(this).val();
        
        // Custom filtering function
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (value === '') return true; // Show all if no filter
            
            const verificationStatus = data[5]; // Email Verified At column
            if (value === 'verified') {
                return verificationStatus !== 'Not verified';
            } else if (value === 'unverified') {
                return verificationStatus === 'Not verified';
            }
            return true;
        });

        usersTable.draw();
        updatePagination(usersTable, '#users-table-pagination');
        
        // Clear the custom filter
        $.fn.dataTable.ext.search.pop();
    });

    // Update info text on draw
    usersTable.on('draw', function() {
        const info = usersTable.page.info();
        $('#table-info').html(
            `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal} entries`
        );
        updatePagination(usersTable, '#users-table-pagination');
    });

    // Initialize pagination
    updatePagination(usersTable, '#users-table-pagination');

    // Handle pagination clicks
    $(document).on('click', '#users-table-pagination .page-link', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        handlePaginationClick(usersTable, action);
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

function showUserInfo(userId) {
    const modalBody = document.getElementById('adminModalBody');
    const modalElement = document.getElementById('adminModal');
    const modalDialog = modalElement.querySelector('.modal-dialog'); // Select the modal dialog
    const modal = new bootstrap.Modal(modalElement);

    // Set modal to a larger size (e.g., modal-lg or modal-xl)
    modalDialog.classList.add('modal-xl'); // Use 'modal-xl' for an extra-large modal

    fetch(`/admin/users/${userId}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch user data');
            return response.text(); // Assuming the server returns HTML
        })
        .then(html => {
            const modalTitle = document.getElementById('adminModalLabel');
            modalTitle.textContent = `User Information: ${userId}`; // Set the title dynamically

            modalBody.innerHTML = html;

            // Optionally hide the modal footer if you don't need it
            const modalFooter = document.querySelector('.modal-footer');
            if (modalFooter) {
                modalFooter.classList.add('d-none'); // Hide footer by adding d-none class
            }

            modal.show();

            const deleteButton = document.getElementById('confirmDeleteButton');
            if (deleteButton) {
                deleteButton.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to undo this action!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('deleteUserForm');
                            const formData = new FormData(form);

                            fetch(form.action, {
                                method: form.method,
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    modal.hide(); // Close the modal
                                    refreshUsersTable()
                                } else {
                                    Swal.fire('Error!', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                            });
                        }
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            modalBody.innerHTML = '<p class="text-danger">Error loading user information.</p>';
            modal.show();
        });
}

function refreshUsersTable() {
    fetch('{{ route("users.index") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Parse JSON
        })
        .then(data => {
            if (data.success) {
                // Update table body
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = ''; // Clear current table rows

                // Populate the table with new user data
                data.users.forEach(user => {
                    const row = `
                        <tr onclick="showUserInfo(${user.id})" style="cursor: pointer;">
                            <td data-label="ID">${user.id}</td>
                            <td data-label="First Name">${user.fname}</td>
                            <td data-label="Last Name">${user.lname}</td>
                            <td data-label="Username">${user.username}</td>
                            <td data-label="Email">${user.email}</td>
                            <td data-label="Email Verified At">${user.email_verified_at || 'Not verified'}</td>
                            <td data-label="Created At">${user.created_at}</td>
                            <td data-label="Builds">
                                <span class="badge bg-primary">${user.total_builds || 0}</span> / 
                                <span class="badge bg-success">${user.published_builds || 0}</span> / 
                                <span class="badge bg-danger">${user.unpublished_builds || 0}</span>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });

                // Update statistics
                document.getElementById('totalUsers').textContent = data.statistics.total;
                document.getElementById('verifiedUsers').textContent = data.statistics.verified;
                document.getElementById('unverifiedUsers').textContent = data.statistics.unverified + ' ' + data.statistics.unverified;
            } else {
                console.error('Failed to fetch users:', data.message);
            }
        })
        .catch(error => {
            console.error('Error refreshing users table:', error);
        });
}
</script>

<style>
/* Control section responsive styles */
.controls-section {
    padding: 0.5rem;
}

/* Search control responsive styles */
.search-wrapper {
    width: 100%;
}

@media (min-width: 768px) {
    .search-wrapper {
        width: auto;
    }
}

/* Make form controls more touch-friendly on mobile */
@media (max-width: 767px) {
    .form-select, .form-control {
        height: 38px;
        padding: 0.375rem 0.75rem;
    }
    
    .search-wrapper input {
        width: 100% !important;
        max-width: none !important;
    }
    
    /* Stack controls vertically on mobile */
    .controls-section > div {
        width: 100%;
    }
    
    /* Center align controls on mobile */
    .d-flex {
        justify-content: center;
    }
    
    /* Adjust spacing for stacked controls */
    .gap-3 {
        gap: 1rem !important;
    }
}

/* Pagination responsive styles */
.pagination {
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.25rem;
}

.page-link {
    padding: 0.375rem 0.75rem;
}

@media (max-width: 767px) {
    .pagination {
        font-size: 0.875rem;
    }
    
    .page-link {
        padding: 0.25rem 0.5rem;
    }
}
</style>
