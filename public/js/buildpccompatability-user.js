class BuildCompatibility {
    constructor() {
        this.selectedComponents = {
            motherboard: null,
            cpu: null,
            gpu: null,
            ram: [], 
            storage: null,
            powerSupply: null,
            case: null
        };
        this.totalTdp = 0;
        this.currentSection = 'motherboard';
        this.ramSlotsUsed = 0;
        this.maxRamSlots = 0;
        this.initializeEventListeners();
    }

    setActiveComponent(componentType) {
        document.querySelectorAll('.list-group-item').forEach(item => 
            item.classList.toggle('active', item.dataset.component === componentType)
        );
        this.currentSection = componentType;
    }

    initializeEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('select-component')) {
                const componentItem = e.target.closest('.component-item');
                this.handleComponentSelection(
                    e.target.dataset.componentType,
                    e.target.dataset.componentId,
                    componentItem.querySelector('.fw-bold').textContent,
                    componentItem.querySelector('img').src,
                    parseInt(componentItem.dataset.tdp) || 0,
                    { ram_slots: parseInt(componentItem.dataset.ramSlots) || 0 }
                );
            }
        });

        // Add clear button event listener
        const clearBtn = document.querySelector('.btn-clear');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearAllComponents());
        }

        // Add publish button event listener
        const publishBtn = document.querySelector('.btn-publish');
        if (publishBtn) {
            publishBtn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('publishBuildModal'));
                modal.show();
            });
        }

        // Add tag checkboxes event listeners
        document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => this.updateTags());
        });

        // Add save build button event listener
        const saveBuildBtn = document.getElementById('saveBuildBtn');
        if (saveBuildBtn) {
            saveBuildBtn.addEventListener('click', () => this.saveBuild());
        }
    }

    clearAllComponents() {
        // Reset all selected components
        this.selectedComponents = {
            motherboard: null,
            cpu: null,
            gpu: null,
            ram: [],
            storage: null,
            powerSupply: null,
            case: null
        };
        this.totalTdp = 0;
        this.ramSlotsUsed = 0;
        this.maxRamSlots = 0;

        // Reset UI
        document.getElementById('total_tdp').value = '0 W';
        document.querySelectorAll('section').forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById('motherboard-section').style.display = 'block';
        
        // Reset active component
        this.setActiveComponent('motherboard');
        
        // Clear selected components display
        this.updateSelectedComponentsDisplay();
        
        // Hide publish button
        document.querySelector('.btn-publish').style.display = 'none';
    }

    checkBuildComplete() {
        const isComplete = this.selectedComponents.motherboard &&
                          this.selectedComponents.cpu &&
                          this.selectedComponents.gpu &&
                          this.selectedComponents.ram.length > 0 &&
                          this.selectedComponents.storage &&
                          this.selectedComponents.powerSupply &&
                          this.selectedComponents.case;
        
        document.querySelector('.btn-publish').style.display = isComplete ? 'block' : 'none';
        return isComplete;
    }

    async handleComponentSelection(componentType, componentId, name, imgSrc, tdp, additionalData = {}) {
        // Ensure correct image path
        const processedImgSrc = imgSrc.startsWith('http') || imgSrc.startsWith('/storage/')
            ? imgSrc
            : `/storage/${imgSrc.replace(/^\/?/, '')}`;
    
        if (componentType === 'ram') {
            if (!Array.isArray(this.selectedComponents.ram)) {
                this.selectedComponents.ram = [];
            }
    
            const ram = {
                id: componentId,
                name,
                image: processedImgSrc,
                tdp,
                speed: additionalData.speed,
                quantity: 1
            };

            this.selectRAM(ram);
        } else {
            this.selectedComponents[componentType] = {
                id: componentId,
                name,
                image: processedImgSrc,
                tdp
            };
            if (componentType === 'motherboard') {
                this.maxRamSlots = additionalData.ram_slots || 4;
                this.ramSlotsUsed = 0;
                this.selectedComponents.ram = [];
                await this.handleMotherboardSelection(componentId);
            }
        }
    
        // Update UI
        this.updateSelectedComponentsDisplay();
        this.calculateTotalTDP();
        this.checkBuildComplete();
    
    
        // Handle section transitions
        const transitions = {
            motherboard: { next: 'cpu', hide: 'motherboard-section', show: 'cpu-section' },
            cpu: { next: 'gpu', hide: 'cpu-section', show: 'gpu-section' },
            gpu: { next: 'ram', hide: 'gpu-section', show: 'ram-section' },
            storage: { next: 'powerSupply', hide: 'storage-section', show: 'psu-section', 
                      action: () => this.calculateAndFetchPowerSupply() },
            powerSupply: { next: 'case', hide: 'psu-section', show: 'case-section',
                          action: async () => {
                              if (this.selectedComponents.motherboard && this.selectedComponents.gpu) {
                                  await this.fetchAndUpdateCases();
                              }
                          }}
        };

        // Only transition if it's not RAM or if RAM slots are full
        if (componentType !== 'ram' || this.ramSlotsUsed === this.maxRamSlots) {
            const transition = transitions[componentType];
            if (transition) {
                if (transition.hide) document.getElementById(transition.hide).style.display = 'none';
                if (transition.show) document.getElementById(transition.show).style.display = 'block';
                if (transition.action) await transition.action();
                this.setActiveComponent(transition.next);
            }
        }

        // If case is selected, disable all remaining select buttons
        if (componentType === 'case') {
            document.querySelectorAll('.select-component').forEach(button => button.disabled = true);
        }

        // Open selectedComponentsOffcanvas only after selecting cases for screen sizes below xl
        if (componentType === 'case' && window.innerWidth < 1200) {
            const offcanvasElement = document.getElementById('selectedComponentsOffcanvas');
            const selectedComponentsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (selectedComponentsOffcanvas) {
                selectedComponentsOffcanvas.toggle();
            } else {
                new bootstrap.Offcanvas(offcanvasElement).show();
            }
        }
    }

    selectRAM(ram) {
        // Check if maximum RAM slots have been reached
        if (this.ramSlotsUsed >= this.maxRamSlots) {
            alert(`Maximum RAM slots (${this.maxRamSlots}) reached!`);
            return;
        }

        // If this is the first RAM module being selected
        if (this.selectedComponents.ram.length === 0) {
            // Show SweetAlert for RAM speed compatibility
            Swal.fire({
                title: 'RAM Speed Compatibility',
                html: `
                    <p style="font-size: 0.9em;">When building a PC, it's crucial to choose RAM modules with the same speed for optimal performance.</p>
                    <br>
                    <strong style="font-size: 0.9em;">Important Recommendations:</strong>
                    <ul style="text-align: left; padding-left: 20px; font-size: 0.9em;">
                        <li>Subsequent RAM modules must match this module's speed: ${ram.speed} MHz</li>
                        <li>Mismatched RAM speeds can cause system instability</li>
                        <li>For best performance, use identical RAM modules</li>
                    </ul>
                `,
                icon: 'warning',
                confirmButtonText: 'I Understand',
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title-small',
                    htmlContainer: 'swal-text-small'
                },
                background: '#1a1a1a',
                color: '#ffffff'
            });
        } 
        // If not the first module, check speed compatibility
        else {
            const firstRamSpeed = this.selectedComponents.ram[0].speed;
            if (ram.speed !== firstRamSpeed) {
                Swal.fire({
                    title: 'RAM Speed Mismatch',
                    html: `
                        <p style="font-size: 0.9em;">You are trying to add a RAM module with a different speed:</p>
                        <p style="font-size: 0.9em;">First RAM Speed: ${firstRamSpeed} MHz</p>
                        <p style="font-size: 0.9em;">New RAM Speed: ${ram.speed} MHz</p>
                        <br>
                        <strong style="font-size: 0.9em;">Recommendation: Use RAM modules with identical speed for optimal performance.</strong>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Add Anyway',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'swal-wide',
                        title: 'swal-title-small',
                        htmlContainer: 'swal-text-small'
                    },
                    background: '#1a1a1a',
                    color: '#ffffff'
                }).then((result) => {
                    // If user cancels, return without adding the RAM
                    if (!result.isConfirmed) {
                        return;
                    }
                    // If user confirms, proceed with adding the RAM
                    this.selectedComponents.ram.push(ram);
                    this.ramSlotsUsed++;
                    this.updateRamUI();
                    this.updateSelectedComponentsDisplay();
                });
                return;
            }
        }

        // Add the RAM module
        this.selectedComponents.ram.push(ram);
        this.ramSlotsUsed++;
        this.updateRamUI();
        this.updateSelectedComponentsDisplay();
    }

    updateSelectedComponentsDisplay() {
        const selectedList = document.getElementById('selected-components');
        if (!selectedList) return;
    
        selectedList.innerHTML = '';
        Object.entries(this.selectedComponents).forEach(([type, component]) => {
            if (type === 'ram' && Array.isArray(component)) {
                component.forEach((ram, index) => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    
                    // Ensure correct image path
                    const imagePath = ram.image.startsWith('http') || ram.image.startsWith('/storage/')
                        ? ram.image
                        : `/storage/${ram.image.replace(/^\/?/, '')}`;
    
                    li.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img src="${imagePath}" alt="${ram.name}" class="me-2" style="width: 50px; height: 50px; object-fit: contain; " loading="lazy">
                            <span>${type.toUpperCase()} Slot ${index + 1}: ${ram.name}</span>
                        </div>
                    `;
                    selectedList.appendChild(li);
                });
            } else if (component) {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
    
                // Ensure correct image path
                const imagePath = component.image.startsWith('http') || component.image.startsWith('/storage/')
                    ? component.image
                    : `/storage/${component.image.replace(/^\/?/, '')}`;
    
                li.innerHTML = `
                    <div class="d-flex align-items-center">
                        <img src="${imagePath}" alt="${component.name}" class="me-2" style="width: 50px; height: 50px; object-fit: contain; " loading="lazy">
                        <span>${type.toUpperCase()}: ${component.name}</span>
                    </div>
                `;
                selectedList.appendChild(li);
            }
        });
    }
    
    

    updateRamUI() {
        const ramSection = document.getElementById('ram-section');
        if (!ramSection) return;

        // Update RAM slots display
        let display = ramSection.querySelector('.ram-slots-display');
        if (!display) {
            display = document.createElement('div');
            display.className = 'ram-slots-display alert alert-info mt-3';
            const componentsDiv = ramSection.querySelector('.ram-components');
            if (componentsDiv) {
                componentsDiv.insertAdjacentElement('beforebegin', display);
            }
        }

        // Update slots display with detailed information
        const ramList = this.selectedComponents.ram
            .map((ram, index) => `<br>• Slot ${index + 1}: ${ram.name}`)
            .join('');

        display.innerHTML = `
            <strong>RAM Slots:</strong> ${this.ramSlotsUsed} / ${this.maxRamSlots} used
            ${ramList}
        `;

        // Handle continue button
        let continueBtn = ramSection.querySelector('.continue-btn');
        if (this.ramSlotsUsed > 0) {
            if (!continueBtn) {
                continueBtn = document.createElement('button');
                continueBtn.className = 'btn btn-dark  mt-3';
                ramSection.appendChild(continueBtn);
            }
            continueBtn.style.display = 'block';
            continueBtn.textContent = `Continue with ${this.ramSlotsUsed} RAM ${this.ramSlotsUsed > 1 ? 's' : ''}`;
            continueBtn.style.position = 'sticky';
            continueBtn.style.bottom = '0';
            continueBtn.style.right = '0';
            continueBtn.style.margin = '10px';

            // Remove old event listener if exists
            continueBtn.replaceWith(continueBtn.cloneNode(true));
            continueBtn = ramSection.querySelector('.continue-btn');

            // Add new event listener
            continueBtn.addEventListener('click', () => {
                document.getElementById('ram-section').style.display = 'none';
                document.getElementById('storage-section').style.display = 'block';
                this.setActiveComponent('storage');
            });
        } else if (continueBtn) {
            continueBtn.style.display = 'none';
        }

        // Auto-progress if max slots are used
        if (this.ramSlotsUsed === this.maxRamSlots) {
            setTimeout(() => {
                document.getElementById('ram-section').style.display = 'none';
                document.getElementById('storage-section').style.display = 'block';
                this.setActiveComponent('storage');
            }, 500);
        }
    }

    calculateTotalTDP() {
        this.totalTdp = Object.entries(this.selectedComponents).reduce((total, [type, component]) => {
            // Skip motherboard and power supply in TDP calculation
            if (type === 'motherboard' || type === 'powerSupply') return total;
            
            if (type === 'ram') {
                return total + (Array.isArray(component) ? component.reduce((ramTotal, ram) => ramTotal + (ram.tdp * ram.quantity), 0) : 0);
            } else if (component && component.tdp) {
                return total + component.tdp;
            }
            return total;
        }, 0);

        const tdpDisplay = document.getElementById('total_tdp');
        if (tdpDisplay) {
            tdpDisplay.value = `${this.totalTdp} W`;
            // Update color based on power supply capacity if one is selected
            if (this.selectedComponents.powerSupply) {
                const psuCapacity = this.selectedComponents.powerSupply.max_tdp;
                tdpDisplay.classList.toggle('text-danger', this.totalTdp > psuCapacity);
                tdpDisplay.classList.toggle('text-success', this.totalTdp <= psuCapacity);
            }
        }
        return this.totalTdp;
    }

    async handleMotherboardSelection(motherboardId) {
        try {
            document.getElementById('motherboard-section').style.display = 'none';
            document.getElementById('cpu-section').style.display = 'block';

            const [cpus, gpus, rams, storages] = await Promise.all([
                this.fetchCompatibleCpus(motherboardId),
                this.fetchCompatibleGpus(motherboardId),
                this.fetchCompatibleRams(motherboardId),
                this.fetchCompatibleStorages(motherboardId)
            ]);

            this.updateCpuOptions(cpus);
            this.updateGpuOptions(gpus);
            this.updateRamOptions(rams);
            this.updateStorageOptions(storages);

            this.resetPowerSupplyAndCase();
        } catch (error) {
            console.error('Error fetching compatible components:', error);
        }
    }

    async fetchAndUpdateCases() {
        const cases = await this.fetchCompatibleCases(
            this.selectedComponents.motherboard.id,
            this.selectedComponents.gpu.id
        );
        this.updateCaseOptions(cases);
    }

    async fetchCompatibleCpus(motherboardId) {
        const response = await fetch(`/build-compatibility/compatible-cpus/${motherboardId}`);
        if (!response.ok) throw new Error('Failed to fetch compatible CPUs');
        return await response.json();
    }

    async fetchCompatibleGpus(motherboardId) {
        const response = await fetch(`/build-compatibility/compatible-gpus/${motherboardId}`);
        if (!response.ok) throw new Error('Failed to fetch compatible GPUs');
        return await response.json();
    }

    async fetchCompatibleRams(motherboardId) {
        const response = await fetch(`/build-compatibility/compatible-rams/${motherboardId}`);
        if (!response.ok) throw new Error('Failed to fetch compatible RAMs');
        return await response.json();
    }

    async fetchCompatibleStorages(motherboardId) {
        const response = await fetch(`/build-compatibility/compatible-storages/${motherboardId}`);
        if (!response.ok) throw new Error('Failed to fetch compatible storage devices');
        return await response.json();
    }

    async fetchCompatibleCases(motherboardId, gpuId) {
        const response = await fetch(`/build-compatibility/compatible-cases/${motherboardId}/${gpuId}`);
        if (!response.ok) throw new Error('Failed to fetch compatible cases');
        return await response.json();
    }

    async fetchCompatiblePowerSupplies() {
        try {
            const response = await fetch('/build-compatibility/compatible-power-supplies', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ totalTdp: this.calculateTotalTDP() })
            });

            if (!response.ok) {
                throw new Error(`Failed to fetch power supplies: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error fetching compatible power supplies:', error);
            throw error;
        }
    }

    addTooltipListeners() {
        const viewSpecsButtons = document.querySelectorAll('.component-item button[data-tooltip-content]');
        
        viewSpecsButtons.forEach(button => {
            // Show tooltip on hover
            button.addEventListener('mouseenter', function(event) {
                const tooltip = document.getElementById('tooltip');
                tooltip.innerHTML = this.dataset.tooltipContent;
                tooltip.style.display = 'block';
                tooltip.classList.add('show');
                
                // Position tooltip
                const tooltipWidth = tooltip.offsetWidth;
                const tooltipHeight = tooltip.offsetHeight;
                const xOffset = 15;
                const yOffset = 15;

                let left = event.pageX + xOffset;
                let top = event.pageY + yOffset;

                // Prevent tooltip from going outside the viewport
                if (left + tooltipWidth > window.innerWidth) {
                    left = event.pageX - tooltipWidth - xOffset;
                }
                if (top + tooltipHeight > window.innerHeight) {
                    top = event.pageY - tooltipHeight - yOffset;
                }

                tooltip.style.left = `${left}px`;
                tooltip.style.top = `${top}px`;
            });

            button.addEventListener('mouseleave', function() {
                const tooltip = document.getElementById('tooltip');
                tooltip.classList.remove('show');
                setTimeout(() => {
                    tooltip.style.display = 'none';
                }, 300);
            });

            // Show tooltip on click
            button.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                const tooltip = document.getElementById('tooltip');
                tooltip.innerHTML = this.dataset.tooltipContent;
                tooltip.style.display = 'block';
                tooltip.classList.add('show');
                
                // Position tooltip
                const tooltipWidth = tooltip.offsetWidth;
                const tooltipHeight = tooltip.offsetHeight;
                const xOffset = 15;
                const yOffset = 15;

                let left = event.pageX + xOffset;
                let top = event.pageY + yOffset;

                // Prevent tooltip from going outside the viewport
                if (left + tooltipWidth > window.innerWidth) {
                    left = event.pageX - tooltipWidth - xOffset;
                }
                if (top + tooltipHeight > window.innerHeight) {
                    top = event.pageY - tooltipHeight - yOffset;
                }

                tooltip.style.left = `${left}px`;
                tooltip.style.top = `${top}px`;
            });
        });
    }

    updateCpuOptions(cpus) {
        const cpuContainer = document.querySelector('.cpu-components');
        if (!cpuContainer) return;

        cpuContainer.innerHTML = cpus.map(cpu => `
            <div class="component-item flex-shrink-0 mb-3" data-tdp="${cpu.tdp || 0}">
                <img src="${cpu.image ? '/storage/' + cpu.image : 'https://via.placeholder.com/80'}" 
                     alt="${cpu.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${cpu.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(cpu)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="cpu" 
                        data-component-id="${cpu.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    updateGpuOptions(gpus) {
        const gpuContainer = document.querySelector('.gpu-components');
        if (!gpuContainer) return;

        gpuContainer.innerHTML = gpus.map(gpu => `
            <div class="component-item flex-shrink-0 mb-3" data-tdp="${gpu.tdp || 0}">
                <img src="${gpu.image ? '/storage/' + gpu.image : 'https://via.placeholder.com/80'}" 
                     alt="${gpu.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${gpu.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(gpu)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="gpu" 
                        data-component-id="${gpu.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    updateRamOptions(rams) {
        const ramContainer = document.querySelector('.ram-components');
        if (!ramContainer) return;

        ramContainer.innerHTML = rams.map(ram => `
            <div class="component-item flex-shrink-0 mb-3" data-tdp="${ram.tdp || 0}">
                <img src="${ram.image ? '/storage/' + ram.image : 'https://via.placeholder.com/80'}" 
                     alt="${ram.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${ram.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(ram)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="ram" 
                        data-component-id="${ram.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    updateStorageOptions(storages) {
        const storageContainer = document.querySelector('.storage-components');
        if (!storageContainer) return;

        storageContainer.innerHTML = storages.map(storage => `
            <div class="component-item flex-shrink-0 mb-3" data-tdp="${storage.tdp || 0}">
                <img src="${storage.image ? '/storage/' + storage.image : 'https://via.placeholder.com/80'}" 
                     alt="${storage.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${storage.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(storage)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="storage" 
                        data-component-id="${storage.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    updatePowerSupplyOptions(powerSupplies) {
        const psuContainer = document.querySelector('.psu-components');
        if (!psuContainer) return;

        psuContainer.innerHTML = powerSupplies.map(psu => `
            <div class="component-item flex-shrink-0 mb-3">
                <img src="${psu.image ? '/storage/' + psu.image : 'https://via.placeholder.com/80'}" 
                     alt="${psu.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${psu.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(psu)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="powerSupply" 
                        data-component-id="${psu.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    updateCaseOptions(cases) {
        const caseContainer = document.querySelector('.case-components');
        if (!caseContainer) return;

        caseContainer.innerHTML = cases.map(pcCase => `
            <div class="component-item flex-shrink-0 mb-3">
                <img src="${pcCase.image ? '/storage/' + pcCase.image : 'https://via.placeholder.com/80'}" 
                     alt="${pcCase.name}"
                     width="80" loading="lazy">
                <div>
                    <span class="d-block fw-bold">${pcCase.name}</span>
                    <button class="btn btn-link text-dark p-0" data-tooltip-content="
                        ${Object.entries(pcCase)
                            .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name'].includes(key))
                            .map(([key, value]) => 
                                `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                        ).join('')}
                    ">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button class="btn btn-dark btn-sm select-component" 
                        data-component-type="case" 
                        data-component-id="${pcCase.id}">
                    Select
                </button>
            </div>
        `).join('');
        
        // Add tooltip listeners after rendering
        this.addTooltipListeners();
    }

    resetPowerSupplyAndCase() {
        const psuContainer = document.querySelector('.psu-components');
        const caseContainer = document.querySelector('.case-components');
        
        if (psuContainer) psuContainer.innerHTML = '<p class="text-muted">Please select other components first</p>';
        if (caseContainer) caseContainer.innerHTML = '<p class="text-muted">Please select a GPU first</p>';
    }

    async calculateAndFetchPowerSupply() {
        try {
            const tdp = this.calculateTotalTDP();
            
            // Show power requirement alert
            await Swal.fire({
                title: 'System Power Requirement',
                html: `
                    <p style="font-size: 0.9em;">Your system's total power requirement:</p>
                    <p style="font-size: 1.2em; font-weight: bold;">${tdp} Watts</p>
                    <br>
                    <strong style="font-size: 0.9em;">Recommendations:</strong>
                    <ul style="text-align: left; padding-left: 20px; font-size: 0.9em;">
                        <li>Choose a power supply with at least 100W headroom above your total requirement</li>
                        <li>Recommended PSU wattage: ${tdp + 100}W or higher</li>
                        <li>Consider future upgrades when selecting your power supply</li>
                    </ul>
                `,
                icon: 'info',
                confirmButtonText: 'Continue',
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title-small',
                    htmlContainer: 'swal-text-small'
                },
                background: '#1a1a1a',
                color: '#ffffff'
            });

            const psuSection = document.querySelector('.psu-components');
            
            if (!psuSection) {
                console.error('PSU section not found');
                return;
            }

            // Show loading state
            psuSection.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';

            const response = await fetch('/build-compatibility/compatible-power-supplies', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ totalTdp: tdp })
            });

            if (!response.ok) {
                throw new Error(`Failed to fetch power supplies: ${response.status}`);
            }

            const powerSupplies = await response.json();

            if (!powerSupplies || powerSupplies.length === 0) {
                psuSection.innerHTML = '<div class="alert alert-warning">No compatible power supplies found.</div>';
                return;
            }

            const sortedPSUs = powerSupplies
                .sort((a, b) => a.max_tdp - b.max_tdp)
                .filter(psu => psu.max_tdp >= tdp);

            const psuHTML = sortedPSUs.map(psu => `
                <div class="component-item flex-shrink-0 mb-3" data-tdp="${psu.max_tdp}">
                    <img src="${psu.image ? '/storage/' + psu.image : 'https://via.placeholder.com/80'}" 
                         alt="${psu.name}"
                         width="80" loading="lazy">
                    <div>
                        <span class="d-block fw-bold">${psu.name}</span>
                        <span class="d-block text-muted">${psu.description || ''}</span>
                        <span class="d-block text-primary">Wattage: ${psu.wattage}W</span>
                        <button class="btn btn-link text-dark p-0" data-tooltip-content="
                            ${Object.entries(psu)
                                .filter(([key]) => !['id', 'image', 'created_at', 'updated_at', 'name', 'description'].includes(key))
                                .map(([key, value]) => 
                                    `<strong>${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</strong>: ${value || 'N/A'}<br>`
                                ).join('')}
                        ">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                    <button class="btn btn-dark btn-sm select-component" 
                            data-component-type="powerSupply" 
                            data-component-id="${psu.id}">
                        Select
                    </button>
                </div>
            `).join('');
            
            psuSection.innerHTML = psuHTML;

            // Add tooltip listeners after rendering
            this.addTooltipListeners();

            // Handle lazy loading for images
            const images = psuSection.querySelectorAll('img[loading="lazy"]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.src;
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } catch (error) {
            console.error('Error fetching power supplies:', error);
            await Swal.fire({
                title: 'Error',
                text: 'Error loading power supplies. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }

    updateTags() {
        const selectedTags = ['Recommended'];
        const tagsContainer = document.getElementById('selected-tags-container');
        
        document.querySelectorAll('#checkbox-container input[type="checkbox"]:checked').forEach(checkbox => {
            selectedTags.push(checkbox.value);
        });

        document.getElementById('tag').value = selectedTags.join(', ');
        
        // Update visual representation of selected tags
        tagsContainer.innerHTML = selectedTags.map(tag => `
            <span class="badge bg-primary me-1 mb-1">${tag}</span>
        `).join('');
    }

    saveBuild() {
        const buildName = document.getElementById('build_name').value;
        const tags = document.getElementById('tag').value;
        const accessories = document.getElementById('accessories').value;

        if (!buildName) {
            alert('Please enter a build name');
            return;
        }

        const buildData = {
            name: buildName,
            tags: tags.split(', '),
            accessories: accessories,
            components: {
                motherboard_id: this.selectedComponents.motherboard?.id,
                cpu_id: this.selectedComponents.cpu?.id,
                gpu_id: this.selectedComponents.gpu?.id,
                ram_ids: this.selectedComponents.ram.map(ram => ram.id),
                storage_id: this.selectedComponents.storage?.id,
                power_supply_id: this.selectedComponents.powerSupply?.id,
                case_id: this.selectedComponents.case?.id
            }
        };

        console.log('Build Data:', buildData);
        
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('publishBuildModal'));
        modal.hide();
    }
}

const buildCompatibility = new BuildCompatibility();

document.addEventListener('DOMContentLoaded', function() {
    buildCompatibility.setActiveComponent('motherboard');

    document.querySelectorAll('.select-component[data-component-type="motherboard"]').forEach(button => {
        button.addEventListener('click', function() {
            const componentItem = this.closest('.component-item');
            const motherboardId = this.dataset.componentId;
            const name = componentItem.querySelector('.fw-bold').textContent;
            const imgSrc = componentItem.querySelector('img').src;
            const tdp = componentItem.dataset.tdp ? parseInt(componentItem.dataset.tdp) : 0;
            const ramSlots = componentItem.dataset.ramSlots ? parseInt(componentItem.dataset.ramSlots) : 0;
            
            buildCompatibility.handleComponentSelection('motherboard', motherboardId, name, imgSrc, tdp, { ram_slots: ramSlots });
        });
    });
});
