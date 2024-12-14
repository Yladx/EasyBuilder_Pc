class PublishManager {
    constructor(componentManager) {
        this.componentManager = componentManager;
        this.selectedTags = [];
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Initialize publish button listeners for both views
        document.querySelectorAll('.btn-publish').forEach(publishBtn => {
            publishBtn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('publishBuildModal'));
                modal.show();
            });
        });

        // Initialize clear button listeners for both views
        document.querySelectorAll('.btn-clear').forEach(clearBtn => {
            clearBtn.addEventListener('click', () => this.clearAllComponents());
        });

        document.querySelectorAll('#checkbox-container input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => this.updateTags());
        });

        const saveBuildBtn = document.getElementById('saveBuildBtn');
        if (saveBuildBtn) {
            saveBuildBtn.addEventListener('click', () => this.saveBuild());
        }

        // Listen for offcanvas close event to sync the views
        const offcanvas = document.getElementById('selectedComponentsOffcanvas');
        if (offcanvas) {
            offcanvas.addEventListener('hidden.bs.offcanvas', () => {
                this.syncViews();
            });
        }
    }

    clearAllComponents() {
        // Clear components through the component manager
        if (this.componentManager) {
            this.componentManager.clearAllComponents();
        }

        // Update both desktop and mobile views
        const desktopView = document.getElementById('selected-components');
        const mobileView = document.getElementById('selected-components-mobile');
        
        if (desktopView) desktopView.innerHTML = '';
        if (mobileView) mobileView.innerHTML = '';

        // Reset TDP displays
        const desktopTdp = document.getElementById('total_tdp');
        const mobileTdp = document.getElementById('total_tdp_mobile');
        
        if (desktopTdp) desktopTdp.value = '0 W';
        if (mobileTdp) mobileTdp.value = '0 W';

        // Hide publish buttons
        document.querySelectorAll('.btn-publish').forEach(btn => {
            btn.style.display = 'none';
        });
    }

    syncViews() {
        const desktopView = document.getElementById('selected-components');
        const mobileView = document.getElementById('selected-components-mobile');
        
        if (desktopView && mobileView) {
            // Sync content between views
            desktopView.innerHTML = mobileView.innerHTML;
        }

        const desktopTdp = document.getElementById('total_tdp');
        const mobileTdp = document.getElementById('total_tdp_mobile');
        
        if (desktopTdp && mobileTdp) {
            // Sync TDP values
            desktopTdp.value = mobileTdp.value;
        }
    }

    updateTags() {
        this.selectedTags = [];
        document.querySelectorAll('#checkbox-container input[type="checkbox"]:checked').forEach(checkbox => {
            this.selectedTags.push(checkbox.value);
        });
        
        document.getElementById('tag').value = this.selectedTags.join(', ');
        
        const container = document.getElementById('selected-tags-container');
        container.innerHTML = '';
        this.selectedTags.forEach(tag => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-1';
            badge.textContent = tag;
            container.appendChild(badge);
        });
    }

    async saveBuild() {
        const buildName = document.getElementById('build_name')?.value || '';
        const accessories = document.getElementById('accessories')?.value || '';
        const buildNote = document.getElementById('build_note')?.value || '';
        const isPublish = document.getElementById('is_publish')?.checked || false;

        if (!buildName) {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter a build name',
                icon: 'error'
            });
            return;
        }

        // Validate all required components are selected
        const requiredComponents = ['motherboard', 'cpu', 'gpu', 'storage', 'powerSupply', 'case'];
        const missingComponents = requiredComponents.filter(comp => 
            !this.componentManager.selectedComponents[comp]?.id
        );

        if (missingComponents.length > 0 || this.componentManager.selectedComponents.ram.length === 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select all required components including at least one RAM module',
                icon: 'error'
            });
            return;
        }

        // Validate tag selection
        if (this.selectedTags.length === 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select at least one tag for your build',
                icon: 'error'
            });
            return;
        }

        // Format RAM IDs into an array of [id, id, ...]
        const ramIds = this.componentManager.selectedComponents.ram.map(ram => ram.id);

        const buildData = {
            build_name: buildName,
            tag: this.selectedTags.join(', '),
            accessories: accessories,
            build_note: buildNote,
            motherboard_id: this.componentManager.selectedComponents.motherboard.id,
            cpu_id: this.componentManager.selectedComponents.cpu.id,
            gpu_id: this.componentManager.selectedComponents.gpu.id,
            storage_id: this.componentManager.selectedComponents.storage.id,
            power_supply_id: this.componentManager.selectedComponents.powerSupply.id,
            case_id: this.componentManager.selectedComponents.case.id,
            ram_id: ramIds,
            published: isPublish
        };

        console.log('Build data being sent:', buildData);

        try {
            const response = await fetch('/build/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(buildData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to save build');
            }

            const buildInfo = await response.json();
            
            Swal.fire({
                title: 'Success!',
                text: 'Your build has been saved',
                icon: 'success'
            }).then(() => {
                bootstrap.Modal.getInstance(document.getElementById('publishBuildModal')).hide();
                window.location.href = '/builds'; // Redirect to builds page
            });
        } catch (error) {
            console.error('Error saving build:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to save build. Please try again.',
                icon: 'error'
            });
        }
    }
}

// Initialize after ComponentManager is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof componentManager !== 'undefined') {
        const publishManager = new PublishManager(componentManager);
    } else {
        console.error('ComponentManager not found. Make sure to load buildpc-components.js before buildpc-modal.js');
    }
});
