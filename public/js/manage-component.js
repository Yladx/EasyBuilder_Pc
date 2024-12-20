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

    // Function to refresh component counts w
    function refreshCounts() {
        fetch("/admin/components/counts")
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

   
    $('#export_excel').click(function () {
        const rows = [];
        const headers = dataTable.columns().header().toArray().map(header => $(header).text());
        
        // Create a mapping of filtered headers to the original indices
        const filteredHeaders = [];
        const headerMap = []; // Maps filtered index to original index
        headers.forEach((header, index) => {
            if (!header.toLowerCase().includes('action') && !header.toLowerCase().includes('image')) {
                filteredHeaders.push(header);
                headerMap.push(index);
            }
        });
        rows.push(filteredHeaders);
    
        const data = dataTable.rows().data().toArray();
        data.forEach((row) => {
            const rowData = [];
            headerMap.forEach(index => {
                rowData.push(row[index]);
            });
            rows.push(rowData);
        });
    
        // Get filter values if applied
        const filterColumn = $('#filterColumn').val();
        const filterValue = $('#filterValue').val();
        let title = `${componentType} Components`;
    
        if (filterColumn && filterValue) {
            const adjustedFilterColumn = filterColumn - 2; // Adjust for removed columns
            const columnName = filteredHeaders[adjustedFilterColumn]; // Corrected filtered column index
            title = `${componentType} ${columnName} - ${filterValue}`;
        }
    
        const worksheet = XLSX.utils.aoa_to_sheet(rows);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Components");
    
        const fileName = `${title}.xlsx`;
        XLSX.writeFile(workbook, fileName);
    });
      
    $('#export_pdf').click(function () {
        const { jsPDF } = window.jspdf; // Ensure jsPDF is correctly loaded
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape, millimeters, A4 size
    
        const table = $('#componentTable').DataTable();
        const headers = table.columns().header().toArray().map(header => $(header).text());
    
        // Filter headers to exclude 'Action' and 'Image'
        const filteredHeaders = [];
        const headerMap = []; // Maps filtered index to original index
        headers.forEach((header, index) => {
            if (!header.toLowerCase().includes('action') && !header.toLowerCase().includes('image')) {
                filteredHeaders.push(header);
                headerMap.push(index);
            }
        });
    
        // Extract filtered rows based on the header mapping
        const data = table.rows().data().toArray();
        const rows = data.map(row => {
            return headerMap.map(index => row[index]);
        });
    
        // Apply filters if specified
        const filterColumn = $('#filterColumn').val();
        const filterValue = $('#filterValue').val();
        let title = `${componentType} Components`;
    
        if (filterColumn && filterValue) {
            const adjustedFilterColumn = filterColumn - 2; // Adjust for removed columns
            const columnName = filteredHeaders[adjustedFilterColumn];
            title = `${componentType} ${columnName} - ${filterValue}`;
        }
    
        // Add title to the PDF
        doc.setFontSize(16);
        doc.text(title, 14, 10);
    
        // Check if autoTable is available
        if (doc.autoTable) {
            doc.autoTable({
                head: [filteredHeaders],
                body: rows,
                startY: 20,
                theme: 'grid',
            });
    
            // Save the PDF
            doc.save(`${title}.pdf`);
        } else {
            console.error("autoTable plugin is not loaded. Please ensure you include the autoTable script.");
        }
    });
    
    
    
    




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
 document.addEventListener('DOMContentLoaded', function () {
    let columns = [];
    let selectedTable = '';

    // Elements
    const selectAllCheckbox = document.getElementById('selectAll');
    const componentCheckboxes = document.querySelectorAll('.component-checkbox');
    const generateReportButton = document.getElementById('generateReport');
    const columnFilterDropdown = document.getElementById('columnFilter');
    const valueFilterInput = document.getElementById('valueFilter');

    // Event listener for "Select All" checkbox
    selectAllCheckbox.addEventListener('change', function () {
        const isChecked = this.checked;

        componentCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
            const targetId = checkbox.dataset.target;
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('show', isChecked);
            }
        });
    });

    // Event listener for individual component checkboxes
    componentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const targetId = this.dataset.target;
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('show', this.checked);
            }

            if (this.checked) {
                selectedTable = this.value;
                fetchColumnsForTable(selectedTable);
            } else if (selectAllCheckbox.checked) {
                selectAllCheckbox.checked = false; // Uncheck "Select All" if a checkbox is unchecked
            }
        });
    });

    // Function to fetch columns for a specific table
    function fetchColumnsForTable(tableName) {
        fetch(`/admin/components/get-columns/${tableName}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch columns');
                }
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data.columns)) {
                    columns = data.columns;
                    populateColumnDropdown(columns);
                } else {
                    console.error('Invalid columns data received:', data);
                }
            })
            .catch(error => console.error('Error fetching columns:', error));
    }

    // Populate the column filter dropdown
    function populateColumnDropdown(columns) {
        columnFilterDropdown.innerHTML = '<option value="">Select Column</option>';
        columns.forEach(column => {
            const option = document.createElement('option');
            option.value = column;
            option.textContent = column;
            columnFilterDropdown.appendChild(option);
        });

        columnFilterDropdown.disabled = columns.length === 0;
        valueFilterInput.disabled = columns.length === 0;
    }

    // Event listener for "Generate Report" button
    generateReportButton.addEventListener('click', function () {
        const selectedComponents = Array.from(componentCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedComponents.length === 0) {
            alert('Please select at least one component type to generate a report.');
            return;
        }

        const column = columnFilterDropdown.value;
        const value = valueFilterInput.value.trim();

        const filters = { column, value };

        console.log('Generating report with the following details:');
        console.log('Components:', selectedComponents);
        console.log('Filters:', filters);

        // You can now send these details to your backend or process them further
    });
});
