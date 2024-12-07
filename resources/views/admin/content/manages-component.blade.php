<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
        <div class="text text-white">Manage Components</div>

        <div class="row mb-4">
            <div class="d-flex flex-wrap">
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #007bff, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">CPU: <span id="cpuCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #28a745, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">GPU: <span id="gpuCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #ffc107, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">MB: <span id="motherboardCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #17a2b8, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">RAM: <span id="ramCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #6c757d, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">SSD: <span id="storageCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #343a40, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">PSU: <span id="powerSupplyCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="width: 14.28%; min-width: 140px; background: linear-gradient(45deg, #dc3545, black); color: white; padding: 10px;">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">CASE: <span id="caseCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group rounded mb-4">
            <label for="componentType" class="text-white mb-2">Select Component:</label>
            <div class="d-flex align-items-center">
                <select class="form-control me-3" id="componentType" style="width: 50vh; background-color: #fff; color: #000; border: 1px solid #adb5bd;">
                    <option value="">-- Select Component --</option>
                    <option value="cpus">CPUs</option>
                    <option value="gpus">GPUs</option>
                    <option value="motherboards">Motherboards</option>
                    <option value="rams">RAMs</option>
                    <option value="storages">Storages</option>
                    <option value="power_supplies">Power Supplies</option>
                    <option value="computer_cases">Cases</option>
                </select>
                <button class="easypc-btn" id="addComponentButton" style="display: none;">Add Component</button>
            </div>
        </div>

        <div id="mainTableContainer" style="display: none;">
         

            <div class="tab-content maintable">

                <div class="bg-transparent d-md-flex flex-wrap d-block align-items-center gap-2 mb-3 p-3 rounded" id="filterSection">
                    <div class="mb-md-0 mb-2">
                        <select class="form-select shadow-sm" id="filterColumn" style="width: 200px; background-color: #2b3035; color: #fff; border: 1px solid #495057;">
                            <option value="">Filter By</option>
                        </select>
                    </div>
                    <div class="mb-md-0 mb-2">
                        <select class="form-select shadow-sm" id="filterValue" style="width: 200px; background-color: #2b3035; color: #fff; border: 1px solid #495057;">
                            <option value="">Select Value</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary shadow-sm px-4" id="applyFilter" style="background-color: #151516d6; border: none;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button class="btn btn-secondary shadow-sm" id="resetFilter" style="background-color: #6c757d; border: none;">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                    <div class="col-md-auto ms-md-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary">
                                <i class="fas fa-search text-secondary"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control bg-light  text-black border-secondary" placeholder="Search..." style="width: 200px;">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="componentTable" class="table table-dark table-striped table-hover" style="width:100%;">
                        <thead id="componentTableHead">
                        </thead>
                        <tbody id="componentTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

   <!-- Include jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <!-- Link to the external JavaScript file -->
   <script src="{{ asset('js/manage-component.js') }}"></script>

 </x-admin-layout>
