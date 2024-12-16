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
        $.fn.dataTable.ext.search.push(function(settings, data) {
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

    // Export to Excel
    $('#export_excel').click(function() {
        const rows = [];
        const columns = ['ID', 'First Name', 'Last Name', 'Username', 'Email', 'Verified At', 'Created At'];

        // Get visible rows
        usersTable.rows({ search: 'applied' }).every(function() {
            const data = this.data();
            rows.push([
                data[0], // ID
                data[1], // First Name
                data[2], // Last Name
                data[3], // Username
                data[4], // Email
                data[5]  // Verified At
            ]);
        });

        // Create Excel file
        const ws = XLSX.utils.aoa_to_sheet([columns, ...rows]);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Users Data');
        XLSX.writeFile(wb, 'users_data.xlsx');
    });

  // Export to PDF
$('#export_pdf').click(function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Table headers
    const headers = ['ID', 'First Name', 'Last Name', 'Username', 'Email', 'Verified At', 'Created At'];

    // Table data
    const data = [];
    usersTable.rows({ search: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
        const rowData = this.data();
        data.push([
            rowData[0], // ID
            rowData[1], // First Name
            rowData[2], // Last Name
            rowData[3], // Username
            rowData[4], // Email
            rowData[5], // Verified At
            rowData[6], // Created At
        ]);
    });

    // Get the filter value to determine the title
    const filterValue = $('#verificationFilter').val();
    let title = 'User Data Report';
    if (filterValue === 'verified') {
        title = 'Verified User Data Report';
    } else if (filterValue === 'unverified') {
        title = 'Unverified User Data Report';
    }

    // Add title
    doc.text(title, 14, 10);

    // Add total users and visible users count
    const totalUsers = usersTable.rows().count();  // Total number of users
    const visibleUsers = usersTable.rows({ search: 'applied' }).count();  // Filtered visible rows count
    doc.text(`Total of All Users: ${totalUsers}`, 14, 20); // Display total users
    doc.setFontSize(8); // Set smaller font size for visible user count
    doc.text(`Total Users: ${visibleUsers}`, 14, 26); // Display visible users count

    // Add table
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 30,  // Start table after the total users text
        margin: { top: 10 },
        theme: 'grid'
    });

    // Save PDF
    doc.save('user_data.pdf');
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
    const modalDialog = modalElement.querySelector('.modal-dialog');
    const modal = new bootstrap.Modal(modalElement);

    // Set modal to a larger size
    modalDialog.classList.add('modal-xl');

    fetch(`/admin/users/${userId}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch user data');
            return response.text();
        })
        .then(html => {
            const modalTitle = document.getElementById('adminModalLabel');
            modalTitle.textContent = `User Information: ${userId}`;

            modalBody.innerHTML = html;

            const modalFooter = document.querySelector('.modal-footer');
            if (modalFooter) {
                modalFooter.classList.add('d-none');
            }

            modal.show();

            const deleteButton = document.getElementById('confirmDeleteButton');
            if (deleteButton) {
                deleteButton.addEventListener('click', function() {
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
                                    modal.hide();
                                    refreshUsersTable();
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
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = '';

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

                document.getElementById('totalUsers').textContent = data.statistics.total;
                document.getElementById('verifiedUsers').textContent = data.statistics.verified;
                document.getElementById('unverifiedUsers').textContent = data.statistics.unverified;
            } else {
                console.error('Failed to fetch users:', data.message);
            }
        })
        .catch(error => {
            console.error('Error refreshing users table:', error);
        });
}
