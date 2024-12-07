document.getElementById('motherboard_id').addEventListener('change', function () {
    const motherboardId = this.value;

    if (!motherboardId) return;

    // Get the TDP and RAM slots of the selected motherboard
    const selectedOption = this.options[this.selectedIndex];
    const motherboardTDP = selectedOption ? parseInt(selectedOption.getAttribute('data-tdp')) || 0 : 0;
    const ramSlots = selectedOption ? parseInt(selectedOption.getAttribute('data-ram-slots')) || 0 : 0;

    // Set motherboard TDP and update total TDP
    setComponentTDP('motherboard_id', motherboardTDP);
    calculateTotalTDP(); // Recalculate total TDP

    // Fetch compatible RAMs and populate the slots
    fetchCompatibleRAMs(motherboardId, ramSlots);

    // Fetch compatible CPUs
    fetchAndUpdateOptions(`/build-compatibility/compatible-cpus/${motherboardId}`, 'cpu_id', 'cpuDiv', 'Select a CPU');
});

function fetchCompatibleRAMs(motherboardId, ramSlots) {
    fetch(`/build-compatibility/compatible-rams/${motherboardId}`)
        .then(response => response.json())
        .then(data => {
            const ramContainer = document.getElementById('ram-container');
            ramContainer.innerHTML = '';

            // Create a hidden input for ram_id array
            const hiddenRamInput = document.createElement('input');
            hiddenRamInput.type = 'hidden';
            hiddenRamInput.name = 'ram_id';
            hiddenRamInput.id = 'ram_id';
            ramContainer.appendChild(hiddenRamInput);

            for (let i = 1; i <= ramSlots; i++) {
                const ramSelect = document.createElement('select');
                ramSelect.className = 'form-select mb-2';
                ramSelect.name = `ram_slot_${i}`;
                ramSelect.id = `ram_slot_${i}`;
                ramSelect.innerHTML = `<option value="" selected disabled>Select RAM for Slot ${i}</option>`;

                data.forEach(ram => {
                    ramSelect.innerHTML += `<option value="${ram.id}" data-tdp="${ram.tdp}">${ram.name} (TDP: ${ram.tdp}W)</option>`;
                });

                ramSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const ramTDP = selectedOption ? parseInt(selectedOption.getAttribute('data-tdp')) || 0 : 0;
                    setComponentTDP(`ram_slot_${i}`, ramTDP);
                    calculateTotalTDP();
                    updateRamIdInput(); // Update hidden input when RAM selection changes
                });

                ramContainer.appendChild(ramSelect);
            }

            document.getElementById('ramDiv').style.display = 'block';
        })
        .catch(error => console.error('Error fetching RAMs:', error));
}

// Function to update the hidden ram_id input
function updateRamIdInput() {
    const ramContainer = document.getElementById('ram-container');
    const ramSelects = ramContainer.querySelectorAll('select[name^="ram_slot_"]');
    const ramValues = Array.from(ramSelects)
        .filter(select => select.value) // Only get selected values
        .map(select => select.value);

    // Update the hidden input with the array of RAM IDs
    const hiddenRamInput = document.getElementById('ram_id');
    if (hiddenRamInput) {
        hiddenRamInput.value = JSON.stringify(ramValues);
    }
}

function fetchAndUpdateOptions(url, selectId, divId, placeholder) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const selectElement = document.getElementById(selectId);
            selectElement.innerHTML = `<option value="" selected disabled>${placeholder}</option>`;
            data.forEach(item => {
                selectElement.innerHTML += `<option value="${item.id}" data-tdp="${item.tdp}">${item.name} (TDP: ${item.tdp}W)</option>`;
            });
            document.getElementById(divId).style.display = data.length > 0 ? 'block' : 'none';

            selectElement.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const tdp = selectedOption ? parseInt(selectedOption.getAttribute('data-tdp')) || 0 : 0;
                setComponentTDP(selectId, tdp);
                calculateTotalTDP(); // Recalculate total TDP

                if (selectId === 'cpu_id') {
                    fetchAndUpdateOptions(`/build-compatibility/compatible-gpus/${document.getElementById('motherboard_id').value}`, 'gpu_id', 'gpuDiv', 'Select a GPU');
                } else if (selectId === 'gpu_id') {
                    const motherboardId = document.getElementById('motherboard_id').value;
                    const gpuId = this.value;

                    fetchCompatibleCases(motherboardId, gpuId);
                    fetchAndUpdateOptions(`/build-compatibility/compatible-storages/${motherboardId}`, 'storage_id', 'storageDiv', 'Select Storage');
                } else if (selectId === 'storage_id') {
                    const totalTdp = calculateTotalTDP();
                    fetchCompatiblePowerSupplies(totalTdp);
                }
            });
        })
        .catch(error => console.error(`Error fetching ${selectId}:`, error));
}

function fetchCompatibleCases(motherboardId, gpuId) {
    fetch(`/build-compatibility/compatible-cases/${motherboardId}/${gpuId}`)
        .then(response => response.json())
        .then(data => {
            const caseSelect = document.getElementById('case_id');
            caseSelect.innerHTML = '<option value="" selected disabled>Select a Case</option>';
            data.forEach(computerCase => {
                caseSelect.innerHTML += `<option value="${computerCase.id}">${computerCase.name}</option>`;
            });
            document.getElementById('caseDiv').style.display = 'block';
        })
        .catch(error => console.error('Error fetching cases:', error));
}

function fetchCompatiblePowerSupplies(totalTdp) {
    fetch(`/build-compatibility/compatible-power-supplies`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ totalTdp }),
    })
        .then(response => response.json())
        .then(data => {
            const powerSupplySelect = document.getElementById('power_supply_id');
            powerSupplySelect.innerHTML = '<option value="" selected disabled>Select a Power Supply</option>';
            data.forEach(psu => {
                powerSupplySelect.innerHTML += `<option value="${psu.id}">${psu.name} (Max TDP: ${psu.max_tdp}W)</option>`;
            });
            document.getElementById('powerSupplyDiv').style.display = 'block';
        })
        .catch(error => console.error('Error fetching power supplies:', error));
}

function setComponentTDP(componentId, tdp) {
    const element = document.getElementById(componentId);
    if (element) {
        element.setAttribute('data-tdp', tdp);
    }
}

function calculateTotalTDP() {
    let totalTDP = 0;
    const components = ['motherboard_id', 'cpu_id', 'gpu_id', 'storage_id'];

    const ramSlots = document.querySelectorAll('[id^="ram_slot_"]');
    ramSlots.forEach(ramSlot => {
        components.push(ramSlot.id);
    });

    components.forEach(componentId => {
        const element = document.getElementById(componentId);
        if (element) {
            const tdp = parseInt(element.getAttribute('data-tdp')) || 0;
            totalTDP += tdp;
        }
    });

    document.getElementById('total_tdp').value = `${totalTDP} W`;
    return totalTDP;
}

// Update form submission handler
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action*="recbuild.store"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect RAM values
            const ramContainer = document.getElementById('ram-container');
            const ramSelects = ramContainer.querySelectorAll('select[name^="ram_slot_"]');
            const ramValues = Array.from(ramSelects)
                .filter(select => select.value) // Only get selected values
                .map(select => select.value);

            console.log('Selected RAM Values:', ramValues);

            if (ramValues.length === 0) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select at least one RAM module',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Create FormData object
            const formData = new FormData(form);

            // Remove any existing ram_id fields from the form and FormData
            form.querySelectorAll('input[name^="ram_id"]').forEach(input => input.remove());
            formData.delete('ram_id');
            formData.delete('ram_slot_1');
            formData.delete('ram_slot_2');

            // Add each RAM value as a separate array entry
            ramValues.forEach(value => {
                formData.append('ram_id[]', value);
            });

            // Send as AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        title: data.title || 'Success',
                        text: data.message,
                        icon: data.type || 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then((result) => {
                        if (data.build) {
                            window.location.reload();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Error creating build. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    }
});
