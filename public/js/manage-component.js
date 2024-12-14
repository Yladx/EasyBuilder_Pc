$(document).ready(function () {
    let columns = [];
    let componentType = '';
    let currentComponentId = null; // Track the current component ID for editing
    let dataTable = null;
    let componentDataTable;

    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    });

    // Function to refresh component counts
    function refreshCounts() {
        fetch("//admin/components/count")
            .then((response) => response.json())
            .then((data) => {
                // Update component counts in the UI
                $('#cpuCount').text(data.cpuCount);
                $('#gpuCount').text(data.gpuCount);
                $('#motherboardCount').text(data.motherboardCount);
                $('#ramCount').text(data.ramCount);
                $('#storageCount').text(data.storageCount);
                $('#powerSupplyCount').text(data.powerSupplyCount);
                $('#caseCount').text(data.caseCount);
            })
            .catch((error) => console.error('Error fetching component counts:', error));
    }

    // Call refreshCounts initially to load counts
    refreshCounts();

    // Handle component type selection
    $('#componentType').on('change', function () {
        const selectedType = $(this).val();
        if (selectedType === '') {
            $('#mainTableContainer').hide();
            $('#addComponentButton').hide();
        } else {
            loadComponentTable(selectedType);
            $('#addComponentButton').show();
        }
    });

    function loadComponentTable(type) {
        componentType = type;
        $('#mainTableContainer').show();
        fetchComponentData(componentType);
    }

    // Fetch component data for the selected type
    function fetchComponentData(type) {
        $.post(`/admin/components/get-data/${type}`)
            .done((response) => {
                if (response.columns && response.data) {
                    columns = response.columns;
                    $('#componentTable, #addComponentButton').show();
                    populateTable(response.data);
                } else {
                    $('#componentTable, #addComponentButton').hide();
                }
            })
            .fail((error) => console.error('Error fetching component data:', error));
    }

    // Populate table with component data
    function populateTable(data) {
        if (!Array.isArray(columns)) {
            console.error("Columns is not an array:", columns);
            return;
        }

        // Destroy existing DataTable if it exists
        if (dataTable) {
            dataTable.destroy();
        }

        // Clear existing table data
        $('#componentTableHead, #componentTableBody').empty();

        // Reorder columns to put image after name
        const reorderedColumns = columns.reduce((acc, col) => {
            if (col.toLowerCase() === 'name') {
                // Find the image column
                const imageCol = columns.find(c => c.toLowerCase().includes('image'));
                if (imageCol) {
                    acc.push(col, imageCol);
                    // Mark image column as processed
                    acc.imageProcessed = true;
                } else {
                    acc.push(col);
                }
            } else if (!acc.imageProcessed || !col.toLowerCase().includes('image')) {
                acc.push(col);
            }
            return acc;
        }, []);

        // Generate table headers with Action as first column
        const headers = [`<th>Action</th>`].concat(reorderedColumns.map(col => `<th>${col}</th>`)).join('');
        $('#componentTableHead').append(`<tr>${headers}</tr>`);

        // Generate table rows with Action as first column and reordered columns
        const rows = data.map(row => {
            const actionButtons = `<td >
                 <button class="btn btn-warning btn-sm me-1 edit-btn" data-id="${row.id}" title="Edit">
    <i class="fas fa-edit"></i>
</button>
<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Delete">
    <i class="fas fa-trash-alt"></i>
</button>
            </td>`;

            const cells = reorderedColumns.map(col => {
                if (col.toLowerCase().includes('image')) {
                    return `<td>
                        ${row[col] ? `<img src="/storage/${row[col]}" alt="${col}" class="img-thumbnail" style="max-width: 100px;">` : ''}
                    </td>`;
                }
                return `<td>${row[col] ?? ''}</td>`;
            }).join('');

            return `<tr>${actionButtons}${cells}</tr>`;
        });

        $('#componentTableBody').append(rows.join(''));

        // Initialize DataTable
        dataTable = $('#componentTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[1, 'asc']], // Sort by second column (first after Action)
            columnDefs: [{
                targets: 0, // First column (Action)
                orderable: false,
                searchable: false
            }],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records..."
            }
        });

        // Handle detached search input
        $('#searchInput').on('keyup', function() {
            dataTable.search(this.value).draw();
        });

        // Hide the default DataTable search box
        $('.dataTables_filter').hide();

        componentDataTable = dataTable;
        initializeFilterDropdowns();
    }

    function initializeFilterDropdowns() {
        const filterColumn = $('#filterColumn');
        const filterValue = $('#filterValue');
        
        // Clear existing options
        filterColumn.empty().append('<option value="">Select Column</option>');
        
        // Get column headers, excluding ID and image columns
        $('#componentTable thead th').each(function(index) {
            const columnName = $(this).text();
            // Skip ID, Actions, and Image columns
            if (!columnName.toLowerCase().includes('id') && 
                !columnName.toLowerCase().includes('action') && 
                !columnName.toLowerCase().includes('image')) {
                filterColumn.append(`<option value="${index}">${columnName}</option>`);
            }
        });

        // Handle column selection change
        filterColumn.on('change', function() {
            const columnIndex = $(this).val();
            filterValue.empty().append('<option value="">Select Value</option>');
            
            if(columnIndex === '') return;

            // Get unique values for selected column
            const uniqueValues = new Set();
            componentDataTable.column(columnIndex).data().each(function(value) {
                // Skip empty, null, or HTML content
                if (value && !value.includes('<') && !value.includes('>')) {
                    uniqueValues.add(value.trim());
                }
            });

            // Add unique values to dropdown
            Array.from(uniqueValues)
                .filter(value => value !== '')
                .sort()
                .forEach(value => {
                    filterValue.append(`<option value="${value}">${value}</option>`);
                });
        });

        // Handle filter button click
        $('#applyFilter').on('click', function() {
            const columnIndex = filterColumn.val();
            const value = filterValue.val();
            
            if(columnIndex !== '' && value !== '') {
                componentDataTable.column(columnIndex)
                    .search('^' + value + '$', true, false)
                    .draw();
            }
        });

        // Handle reset button click
        $('#resetFilter').on('click', function() {
            filterColumn.val('');
            filterValue.empty().append('<option value="">Select Value</option>');
            componentDataTable.search('').columns().search('').draw();
        });
    }

    // Add new component
    $('#addComponentButton').on('click', () => {
        currentComponentId = null; // Reset ID
        fetchComponentColumns(componentType, 'Add Component');
    });

    // Edit component
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        currentComponentId = id; // Set ID for editing
        openEditModal(id);
    });

    // Fetch component columns and data for editing
    function openEditModal(id) {
        $.get(`/admin/components/${componentType}/${id}`)
            .done((response) => {
                if (response.data) {
                    fetchComponentColumns(componentType, 'Edit Component', response.data);
                } else {
                    Swal.fire('Error', 'No data found for this component.', 'error');
                }
            })
            .fail((error) => console.error('Error fetching component data:', error));
    }

    // Fetch component columns and show modal
    function fetchComponentColumns(type, modalTitle, data = {}) {
        $.post(`/admin/components/get-data/${type}`)
            .done((response) => {
                if (response.columns) {
                    showAdminModal(modalTitle, response.columns, data, response.comments);
                }
            })
            .fail((error) => console.error('Error fetching component fields:', error));
    }

    // Show modal with form
    function showAdminModal(title, fields, data = {}, comments = {}) {
        $('#adminModalLabel').text(title);
        $('#adminModalBody').empty();
        const modalFooter = $('#adminModal .modal-footer');
        modalFooter.empty();

        const form = $('<form>', { id: 'componentForm', enctype: 'multipart/form-data' });

        // Display image at the top if available
        let imageDisplayed = false;
        fields.forEach((field) => {
            if (field.toLowerCase().includes('image') && !imageDisplayed) {
                const value = data[field] ?? '';
                const imageHtml = value
                    ? `<img src="/storage/${value}" alt="${field}" class="img-thumbnail mb-4" style="width: 250px; height: 250px; object-fit: contain; display: block; margin: 0 auto;">`
                    : '';
                if (imageHtml) {
                    form.append(imageHtml);
                    imageDisplayed = true;
                }
            }
        });

        fields.forEach((field) => {
            if (['id', 'created_at', 'updated_at'].includes(field)) return;

            const value = data[field] ?? '';
            const comment = comments[field] ?? ''; // Get the comment for the field

            if (field.toLowerCase().includes('image')) {
                form.append(
                    `<div class="mb-3">
                        <label for="${field} :" class="form-label"><strong>${field.toUpperCase()}</strong> <span class="text-muted">(${comment})</span></label>
                        <input type="file" class="form-control" name="${field}" accept="image/*">
                    </div>`
                );
            } else {
                form.append(
                    `<div class="mb-3">
                        <label for="${field} :" class="form-label"><strong>${field.toUpperCase()}</strong> <span class="text-muted">(${comment})</span></label>
                        <input type="text" class="form-control" name="${field}" value="${value}">
                    </div>`
                );
            }
        });

        $('#adminModalBody').append(form);
        modalFooter.append(
            `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="button" class="btn btn-primary" id="saveComponentButton">Save</button>`
        );

        $('#adminModal').modal('show');

        // Handle Save button click
        $('#saveComponentButton').off('click').on('click', function () {
            saveComponentData();
        });
    }

    // Save component data
    function saveComponentData() {
        const formData = new FormData($('#componentForm')[0]);
        const url = currentComponentId
            ? `/admin/components/${componentType}/${currentComponentId}/update`
            : `/admin/components/${componentType}/add`;

        if (currentComponentId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: (response) => {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#adminModal').modal('hide');
                    fetchComponentData(componentType);
                    refreshCounts(); // Refresh counts after adding/updating
                } else {
                    Swal.fire('Error', 'An error occurred.', 'error');
                }
            },
            error: (xhr) => {
                let errorMsg = 'An error occurred while saving the component.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    errorMsg += `\n${errors}`;
                }
                Swal.fire('Error', errorMsg, 'error');
            },
        });
    }

    // Delete component
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to undo this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                deleteComponent(id);
            }
        });
    });

    function deleteComponent(id) {
        $.ajax({
            url: `/admin/components/${componentType}/${id}/delete`,
            type: 'DELETE',
            success: (response) => {
                if (response.success) {
                    Swal.fire('Deleted!', 'Component deleted successfully.', 'success');
                    fetchComponentData(componentType);
                    refreshCounts(); // Refresh counts after deletion
                } else {
                    Swal.fire('Error', 'Failed to delete component.', 'error');
                }
            },
            error: (error) => console.error('Error deleting component:', error),
        });
    }
});
