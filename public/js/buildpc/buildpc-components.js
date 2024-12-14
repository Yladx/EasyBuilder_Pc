class ComponentManager {
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
        this.ramSlotsUsed = 0;
        this.maxRamSlots = 0;
        this.currentSection = 'motherboard';
        this.steps = [
            { id: 'motherboard', title: 'Motherboard', description: 'Select a motherboard to start your build' },
            { id: 'cpu', title: 'CPU', description: 'Choose a compatible processor' },
            { id: 'gpu', title: 'Graphics Card', description: 'Select a graphics card' },
            { id: 'ram', title: 'RAM', description: 'Add memory modules' },
            { id: 'storage', title: 'Storage', description: 'Choose storage devices' },
            { id: 'powerSupply', title: 'Power Supply', description: 'Select a power supply' },
            { id: 'case', title: 'Case', description: 'Choose a computer case' }
        ];
        this.currentStepIndex = 0;
        this.initializeEventListeners();
        this.initializeStepIndicators();
    }

    initializeStepIndicators() {
        const stepsContainer = document.createElement('div');
        stepsContainer.className = 'build-steps-container mb-4';
        stepsContainer.innerHTML = `
            <div class="progress mb-3">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="current-step-info">
                <h4 class="step-title mb-2"></h4>
                <p class="step-description text-muted"></p>
            </div>
        `;
        
        const firstSection = document.querySelector('section');
        if (firstSection) {
            firstSection.parentNode.insertBefore(stepsContainer, firstSection);
        }
        
        this.updateStepIndicators();
    }

    updateStepIndicators() {
        const progressBar = document.querySelector('.progress-bar');
        const stepTitle = document.querySelector('.step-title');
        const stepDescription = document.querySelector('.step-description');
        
        if (!progressBar || !stepTitle || !stepDescription) return;

        const currentStep = this.steps[this.currentStepIndex];
        const progress = ((this.currentStepIndex) / (this.steps.length - 1)) * 100;
        
        progressBar.style.width = `${progress}%`;
        stepTitle.textContent = `Step ${this.currentStepIndex + 1}: ${currentStep.title}`;
        stepDescription.textContent = currentStep.description;
    }

    setActiveComponent(componentType) {
        document.querySelectorAll('.list-group-item').forEach(item => 
            item.classList.toggle('active', item.dataset.component === componentType)
        );
        this.currentSection = componentType;
        this.currentStepIndex = this.steps.findIndex(step => step.id === componentType);
        this.updateStepIndicators();
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

        const clearBtn = document.querySelector('.btn-clear');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearAllComponents());
        }
    }

    clearAllComponents() {
        // Reset all internal state
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
        this.currentStepIndex = 0;

        // Clear TDP display
        const tdpElement = document.getElementById('total_tdp');
        if (tdpElement) {
            tdpElement.value = '0 W';
        }

        // Hide all component sections
        const sections = [
            'motherboard-section',
            'cpu-section',
            'gpu-section',
            'ram-section',
            'storage-section',
            'psu-section',
            'case-section'
        ];

        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'none';
            }
        });

        // Show only motherboard section
        const motherboardSection = document.getElementById('motherboard-section');
        if (motherboardSection) {
            motherboardSection.style.display = 'block';
        }

        // Reset all component buttons
        document.querySelectorAll('.select-component').forEach(button => {
            button.classList.remove('selected');
            button.textContent = 'Select';
            button.disabled = false;
        });

        // Clear any active selections
        document.querySelectorAll('.component-item.selected').forEach(item => {
            item.classList.remove('selected');
        });

        // Reset RAM UI elements
        const ramDisplay = document.querySelector('.ram-slots-display');
        if (ramDisplay) {
            ramDisplay.textContent = 'RAM Slots Used: 0 / 0';
            ramDisplay.style.display = 'none';
        }

        const continueBtn = document.querySelector('.continue-btn');
        if (continueBtn) {
            continueBtn.style.display = 'none';
        }

        // Hide publish button
        const publishBtn = document.querySelector('.btn-publish');
        if (publishBtn) {
            publishBtn.style.display = 'none';
        }

        // Clear selected components display
        const selectedComponentsContainer = document.getElementById('selected-components');
        if (selectedComponentsContainer) {
            selectedComponentsContainer.innerHTML = '';
        }

        // Reset active component to motherboard and update UI
        this.setActiveComponent('motherboard');
        this.updateStepIndicators();
        this.updateSelectedComponentsDisplay();

        // Force refresh of component visibility
        setTimeout(() => {
            sections.forEach(sectionId => {
                if (sectionId !== 'motherboard-section') {
                    const section = document.getElementById(sectionId);
                    if (section) {
                        section.style.display = 'none';
                    }
                }
            });
            if (motherboardSection) {
                motherboardSection.style.display = 'block';
            }
        }, 0);
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
        // Extract the relative path if it's a full URL
        const imageUrl = imgSrc.includes('/storage/') ? imgSrc : `/storage/${imgSrc}`;
        
        if (componentType === 'ram') {
            if (this.ramSlotsUsed < this.maxRamSlots) {
                this.selectedComponents.ram.push({ id: componentId, name, imgSrc: imageUrl, tdp, quantity: 1 });
                this.ramSlotsUsed++;
                this.updateRamUI();
            }
        } else {
            this.selectedComponents[componentType] = { id: componentId, name, imgSrc: imageUrl, tdp };
            if (componentType === 'motherboard') {
                this.maxRamSlots = additionalData.ram_slots;
                this.ramSlotsUsed = 0;
                this.selectedComponents.ram = [];
            }
        }

        this.updateSelectedComponentsDisplay();
        this.calculateTotalTDP();
        this.checkBuildComplete();
        this.handleSectionTransition(componentType, componentId);
    }

    async handleSectionTransition(componentType, componentId) {
        const transitions = {
            motherboard: { next: 'cpu', show: 'cpu-section' },
            cpu: { next: 'gpu', hide: 'cpu-section', show: 'gpu-section' },
            gpu: { next: 'ram', hide: 'gpu-section', show: 'ram-section' },
            storage: { next: 'powerSupply', hide: 'storage-section', show: 'psu-section' },
            powerSupply: { next: 'case', hide: 'psu-section', show: 'case-section' }
        };

        const transition = transitions[componentType];
        if (transition) {
            if (transition.hide) {
                const hideSection = document.getElementById(transition.hide);
                if (hideSection) {
                    hideSection.style.display = 'none';
                }
            }
            if (transition.show) {
                const showSection = document.getElementById(transition.show);
                if (showSection) {
                    showSection.style.display = 'block';
                }
            }
            this.setActiveComponent(transition.next);
        }
    }

    updateRamUI() {
        const ramSection = document.getElementById('ram-section');
        let display = ramSection.querySelector('.ram-slots-display');
        if (!display) {
            display = document.createElement('div');
            display.className = 'ram-slots-display alert alert-info mt-3';
            ramSection.querySelector('.ram-components').insertAdjacentElement('beforebegin', display);
        }
        display.textContent = `RAM Slots Used: ${this.ramSlotsUsed} / ${this.maxRamSlots}`;

        if (this.ramSlotsUsed === this.maxRamSlots) {
            document.getElementById('ram-section').style.display = 'none';
            document.getElementById('storage-section').style.display = 'block';
            this.setActiveComponent('storage');
        } else if (this.ramSlotsUsed > 0) {
            let continueBtn = ramSection.querySelector('.continue-btn');
            if (!continueBtn) {
                continueBtn = document.createElement('button');
                continueBtn.className = 'btn btn-primary continue-btn mt-3';
                ramSection.appendChild(continueBtn);
                continueBtn.addEventListener('click', () => {
                    document.getElementById('ram-section').style.display = 'none';
                    document.getElementById('storage-section').style.display = 'block';
                    this.setActiveComponent('storage');
                });
            }
            continueBtn.style.display = 'block';
            continueBtn.textContent = `Continue with ${this.ramSlotsUsed} RAM stick${this.ramSlotsUsed > 1 ? 's' : ''}`;
        }
    }

    calculateTotalTDP() {
        this.totalTdp = ['cpu', 'gpu', 'storage'].reduce((total, type) => {
            return total + (this.selectedComponents[type]?.tdp || 0);
        }, 0) + this.selectedComponents.ram.reduce((total, ram) => total + (ram.tdp * ram.quantity), 0);

        document.getElementById('total_tdp').value = `${this.totalTdp} W`;
        return this.totalTdp;
    }

  
}

const componentManager = new ComponentManager();

document.addEventListener('DOMContentLoaded', function() {
    componentManager.setActiveComponent('motherboard');
});