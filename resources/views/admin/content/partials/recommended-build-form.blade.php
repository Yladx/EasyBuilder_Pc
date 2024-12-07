<style>
    .selected-tag {
      display: inline-flex;
      align-items: center;
      margin: 5px;
      padding: 5px 10px;
      background-color: #303030;
      color: #fff;
      border-radius: 20px;
  }

  .selected-tag button {
      margin-left: 5px;
      background: transparent;
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 12px;
  }

  </style>

    <!-- Sticky and Absolute Total TDP -->
    <div class="position-absolute sticky-top  " style="top: 0px; right: 0px; z-index: 1030; width: 80px;">
        <label for="total_tdp" class="visually-hidden">Total TDP</label>
        <input
            type="text"
            class="form-control text-center fw-bold"
            id="total_tdp"
            name="total_tdp"
            value="0 W"
            readonly
            style="width: 100%; height: 40px;">
    </div>


                  <form id="recommendedBuildForm" action="{{route('recbuild.store')}}" method="POST">
                      @csrf

                      <div class="row">

                      <div class="col-4">
                      <!-- Build Name -->
                      <div class="mb-3">
                          <label for="build_name" class="form-label">Build Name</label>
                          <input type="text" class="form-control" id="build_name" name="build_name" required>
                      </div>

                    <!-- Build Note -->
                    <div class="mb-3">
                        <label for="build_note" class="form-label">Build Note (Optional)</label>
                        <textarea class="form-control" id="build_note" name="build_note" rows="3"></textarea>
                    </div>
                    
                      <div class="mb-3  " >
                          <label for="tag" class="form-label">Tags</label>         
                        <input type="hidden" id="tag" name="tag" value="Recommended">
                    </div>

                      <div id="selected-tags-container">
                        <!-- 'Recommend' Tag is added automatically and cannot be cleared -->
                        <span class="badge bg-success text-white me-2" data-permanent="true">Recommended</span>
                    </div>

                    

                      <div class="mb-3">
                          <label class="form-label">Select Tags</label>
                          <div id="checkbox-container">
                              <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="checkbox-Gaming" value="Gaming" onchange="updateTags()">
                                  <label class="form-check-label" for="checkbox-Gaming">Gaming</label>
                              </div>
                              <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="checkbox-Office" value="Office" onchange="updateTags()">
                                  <label class="form-check-label" for="checkbox-Office">Office</label>
                              </div>
                              <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="checkbox-School" value="School" onchange="updateTags()">
                                  <label class="form-check-label" for="checkbox-School">School</label>
                              </div>
                          </div>
                      </div>

                      </div>

                      <div class="col-4">
                 <!-- Motherboard Selection -->
        <div class="mb-3" id="motherboardDiv">
            <label for="motherboard_id" class="form-label">Motherboard</label>
            <select class="form-select" id="motherboard_id" name="motherboard_id" required>
                <option value="" selected disabled>Select a Motherboard</option>
                @foreach ($motherboards as $motherboard)
                    <option value="{{ $motherboard->id }}"  data-ram-slots="{{ $motherboard->ram_slots }}">
                        {{ $motherboard->name }} , RAM Slots: {{ $motherboard->ram_slots }}
                    </option>
                @endforeach
            </select>
        </div>


                      <!-- CPU Selection -->
                      <div class="mb-3" id="cpuDiv" style="display: none;">
                          <label for="cpu_id" class="form-label">CPU</label>
                          <select class="form-select" id="cpu_id" name="cpu_id" required>
                              <option value="" selected disabled>Select a CPU</option>
                          </select>
                      </div>

                      <!-- GPU Selection -->
                      <div class="mb-3" id="gpuDiv" style="display: none;">
                          <label for="gpu_id" class="form-label">GPU</label>
                          <select class="form-select" id="gpu_id" name="gpu_id" required>
                              <option value="" selected disabled>Select a GPU</option>
                          </select>
                      </div>
                  
                      <!-- RAM Selection -->

                    <!-- RAM Selection -->
                    <div class="mb-3" id="ramDiv" style="display: none;">
                        <label for="ram_id" class="form-label">RAM</label>
                        <div id="ram-container"></div>
                    </div>
                    </div>
                    <div class="col-4">
                      <!-- Storage Selection -->
                      <div class="mb-3" id="storageDiv" style="display: none;">
                          <label for="storage_id" class="form-label">Storage</label>
                          <select class="form-select" id="storage_id" name="storage_id" required>
                              <option value="" selected disabled>Select Storage</option>
                          </select>
                      </div>

                      <!-- Power Supply Selection -->
                      <div id="powerSupplyDiv" class="mb-3" style="display: none;">
                        <label for="power_supply_id" class="form-label">Power Supply</label>
                        <select id="power_supply_id" class="form-select" name="power_supply_id"></select>
                    </div>

                      <!-- Computer Case Selection -->
                      <div class="mb-3" id="caseDiv" style="display: none;">
                          <label for="case_id" class="form-label">Computer Case</label>
                          <select class="form-select" id="case_id" name="case_id" required>
                              <option value="" selected disabled>Select a Computer Case</option>
                          </select>
                      </div>

                      <!-- Accessories -->
                      <div class="mb-3">
                          <label for="accessories" class="form-label">Accessories</label>
                          <textarea class="form-control" id="accessories" name="accessories" rows="3"></textarea>
                      </div>


                      <div class="mb-3 form-check">
                          <input type="checkbox" class="form-check-input" id="published" name="published" value="1">
                          <label class="form-check-label" for="published">Publish your Build</label>
                      </div>
                      </div>


                      <button type="submit" class="btn btn-primary">Create Build</button>
                      </div>
                  </form>
                </div>


