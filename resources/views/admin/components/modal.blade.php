<link rel="stylesheet" href="{{ asset('css/build/accordion.css') }}">

<!-- Admin Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminModalLabel">Admin Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="adminModalBody">
                <!-- Content will be loaded dynamically here -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>



            <div class="modal-footer">
                <!-- Buttons will be added dynamically -->
            </div>


        </div>
    </div>
</div>
<div class="modal fade" id="componentreport" tabindex="-1" aria-labelledby="componentreportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="componentreportLabel">Generate Component Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label><input type="checkbox" id="selectAll"> Select All Components</label>
                <div id="componentCheckboxes">
                    <!-- CPUs -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="cpus" data-target="cpuFilter"> CPUs</label>
                        <div id="cpuFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterCpus">Filter by Column:</label>
                                <select id="columnFilterCpus" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterCpus">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterCpus">
                            </div>
                        </div>
                    </div>
                    <!-- GPUs -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="gpus" data-target="gpuFilter"> GPUs</label>
                        <div id="gpuFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterGpus">Filter by Column:</label>
                                <select id="columnFilterGpus" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterGpus">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterGpus">
                            </div>
                        </div>
                    </div>
                    <!-- Motherboards -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="motherboards" data-target="motherboardFilter"> Motherboards</label>
                        <div id="motherboardFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterMotherboards">Filter by Column:</label>
                                <select id="columnFilterMotherboards" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterMotherboards">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterMotherboards">
                            </div>
                        </div>
                    </div>
                    <!-- RAMs -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="rams" data-target="ramFilter"> RAMs</label>
                        <div id="ramFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterRams">Filter by Column:</label>
                                <select id="columnFilterRams" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterRams">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterRams">
                            </div>
                        </div>
                    </div>
                    <!-- Storages -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="storages" data-target="storageFilter"> Storages</label>
                        <div id="storageFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterStorages">Filter by Column:</label>
                                <select id="columnFilterStorages" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterStorages">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterStorages">
                            </div>
                        </div>
                    </div>
                    <!-- Power Supplies -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="power_supplies" data-target="powerSupplyFilter"> Power Supplies</label>
                        <div id="powerSupplyFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterPowerSupplies">Filter by Column:</label>
                                <select id="columnFilterPowerSupplies" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterPowerSupplies">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterPowerSupplies">
                            </div>
                        </div>
                    </div>
                    <!-- Computer Cases -->
                    <div>
                        <label><input type="checkbox" class="component-checkbox" value="computer_cases" data-target="computerCaseFilter"> Cases</label>
                        <div id="computerCaseFilter" class="filter-section collapse">
                            <div class="mt-3">
                                <label for="columnFilterComputerCases">Filter by Column:</label>
                                <select id="columnFilterComputerCases" class="form-select">
                                    <option value="">Select Column</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="valueFilterComputerCases">Filter Value:</label>
                                <input type="text" class="form-control" id="valueFilterComputerCases">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="generateReport">Generate Report</button>
            </div>
        </div>
    </div>
</div>