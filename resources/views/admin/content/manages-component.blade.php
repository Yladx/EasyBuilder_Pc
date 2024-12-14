<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
        <div class="text text-white">Manage Components</div>

        

        <div class="row mb-3">
            <div class="d-flex flex-wrap stats-container">
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(13, 71, 161, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">CPU: <span id="cpuCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(46, 125, 50, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">GPU: <span id="gpuCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(245, 124, 0, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">MB: <span id="motherboardCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(0, 151, 167, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">RAM: <span id="ramCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(84, 110, 122, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">SSD: <span id="storageCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(55, 71, 79, 0.85), rgba(0, 0, 0, 0.95));">
                    <div class="text-center">
                        <span class="fw-bold" style="font-size: 0.75rem;">PSU: <span id="powerSupplyCount" style="font-size: 0.7rem;">0</span></span>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(225deg, rgba(198, 40, 40, 0.85), rgba(0, 0, 0, 0.95));">
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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn gradient-btn px-4" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-filter me-1"></i> Filter Options
                    </button>
                    <div class="col-md-auto">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control bg-light text-black border-secondary" placeholder="Search..." style="width: 200px;">
                        </div>
                    </div>
                </div>

                <div class="collapse mb-3" id="filterCollapse">
                    <div class="bg-transparent d-md-flex flex-wrap d-block align-items-center gap-2 p-3 rounded border border-secondary" id="filterSection">
                        <div class="mb-md-0 mb-2">
                            <select class="form-select shadow-sm" id="filterColumn" style="width: 200px; border: 1px solid #495057;">
                                <option value="">Filter By</option>
                            </select>
                        </div>
                        <div class="mb-md-0 mb-2">
                            <select class="form-select shadow-sm" id="filterValue" style="width: 200px; border: 1px solid #495057;">
                                <option value="">Select Value</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <style>
                                .gradient-btn {
                                    background: linear-gradient(45deg, #00000099, #00000099, #0f1924a9, #2a5478a9);
                                    border: none;
                                    color: #fff;
                                    transition: all 0.3s ease;
                                }

                                .gradient-btn:hover {
                                    background: linear-gradient(45deg, #000000cc, #0f1924cc, #1a2733cc, #2a5378cc);
                                    color: #fff;
                                    transform: translateY(-1px);
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                }

                                .gradient-btn.reset {
                                    background: linear-gradient(45deg, #00000099, #00000099, #1a1a1aa9, #8B0000a9);
                                }

                                .gradient-btn.reset:hover {
                                    background: linear-gradient(45deg, #000000cc, #1a1a1acc, #2d1810cc, #8B0000cc);
                                }
                            </style>
                            <button class="btn gradient-btn px-4" id="applyFilter">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button class="btn gradient-btn reset px-4" id="resetFilter">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
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
