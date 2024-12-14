// Expanded FAQ content with more compatibility details
const faqContent = {
    motherboard: {
        title: '💡 Motherboard Compatibility Deep Dive',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>Socket Compatibility</h5>
                    <p>Exact CPU socket match required (e.g., AM4, LGA 1700)</p>
                </div>
                <div class="tip-item">
                    <h5>RAM Generations</h5>
                    <p>Support for DDR4/DDR5, max speed, and capacity</p>
                </div>
                <div class="tip-item">
                    <h5>PCIe Versions</h5>
                    <p>Check PCIe slot versions for GPU and expansion cards</p>
                </div>
                <div class="tip-item">
                    <h5>Power Delivery</h5>
                    <p>VRM quality crucial for stable CPU performance</p>
                </div>
                <div class="tip-item">
                    <h5>M.2 Slots</h5>
                    <p>Number and PCIe generation of NVMe slots</p>
                </div>
            </div>
        `
    },
    cpu: {
        title: '💡 CPU Compatibility Essentials',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>Generational Compatibility</h5>
                    <p>Ensure motherboard supports CPU generation</p>
                </div>
                <div class="tip-item">
                    <h5>BIOS/UEFI Updates</h5>
                    <p>May require firmware update for newer CPUs</p>
                </div>
                <div class="tip-item">
                    <h5>Cooling Requirements</h5>
                    <p>Check TDP and socket mounting compatibility</p>
                </div>
                <div class="tip-item">
                    <h5>Memory Support</h5>
                    <p>Supported RAM types and maximum speeds</p>
                </div>
                <div class="tip-item">
                    <h5>Power Consumption</h5>
                    <p>Verify PSU can handle CPU power requirements</p>
                </div>
            </div>
        `
    },
    gpu: {
        title: '💡 GPU Compatibility Breakdown',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>PCIe Slot Compatibility</h5>
                    <p>Ensure matching PCIe generation and physical slot</p>
                </div>
                <div class="tip-item">
                    <h5>Power Connector Requirements</h5>
                    <p>Check 6-pin, 8-pin, or 12-pin connector needs</p>
                </div>
                <div class="tip-item">
                    <h5>Physical Dimensions</h5>
                    <p>Verify GPU length fits case dimensions</p>
                </div>
                <div class="tip-item">
                    <h5>Power Supply Wattage</h5>
                    <p>Dedicated power budget for high-end GPUs</p>
                </div>
                <div class="tip-item">
                    <h5>Thermal Design</h5>
                    <p>Ensure adequate case airflow and cooling</p>
                </div>
            </div>
        `
    },
    ram: {
        title: '💡 RAM Compatibility',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>DDR Generation</h5>
                    <p>Must match motherboard/CPU support (DDR4/DDR5)</p>
                </div>
                <div class="tip-item">
                    <h5>Speed Support</h5>
                    <p>Check motherboard's supported RAM speeds</p>
                </div>
                <div class="tip-item">
                    <h5>Capacity Limits</h5>
                    <p>Verify motherboard's maximum RAM capacity</p>
                </div>
            </div>
        `
    },
    storage: {
        title: '💡 Storage Compatibility',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>Interface Type</h5>
                    <p>Check motherboard support (SATA/M.2/NVMe)</p>
                </div>
                <div class="tip-item">
                    <h5>Form Factor</h5>
                    <p>Ensure case has mounting points (2.5"/3.5"/M.2)</p>
                </div>
                <div class="tip-item">
                    <h5>PCIe Generation</h5>
                    <p>For NVMe drives, check motherboard M.2 slot version</p>
                </div>
            </div>
        `
    },
    powerSupply: {
        title: '💡 PSU Compatibility',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>Wattage</h5>
                    <p>Must exceed total system power consumption</p>
                </div>
                <div class="tip-item">
                    <h5>Form Factor</h5>
                    <p>Check case PSU form factor support (ATX/SFX)</p>
                </div>
                <div class="tip-item">
                    <h5>Connectors</h5>
                    <p>Verify all required power connectors are available</p>
                </div>
            </div>
        `
    },
    case: {
        title: '💡 Case Compatibility',
        content: `
            <div class="tips-box">
                <div class="tip-item">
                    <h5>Motherboard Size</h5>
                    <p>Must support your motherboard form factor</p>
                </div>
                <div class="tip-item">
                    <h5>GPU Length</h5>
                    <p>Check maximum GPU length support</p>
                </div>
                <div class="tip-item">
                    <h5>PSU Type</h5>
                    <p>Verify PSU form factor support (ATX/SFX)</p>
                </div>
            </div>
        `
    }
};

// Update styles for dark theme
const style = document.createElement('style');
style.textContent = `
    .tips-box {
        background: #1e1e1e;
        border: 1px solid #333;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .tip-item {
        padding: 12px;
        border-bottom: 1px solid #333;
        transition: background-color 0.2s;
    }

    .tip-item:hover {
        background-color: #2a2a2a;
    }

    .tip-item:last-child {
        border-bottom: none;
    }

    .tip-item h5 {
        color: #4a9eff;
        font-size: 0.95rem;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .tip-item p {
        color: #b0b0b0;
        font-size: 0.85rem;
        margin: 0;
    }

    .tips-modal .swal2-popup {
        background: #1e1e1e !important;
        border: 1px solid #333;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .tips-modal .swal2-title {
        color: #4a9eff;
        border-bottom: 1px solid #333;
        padding-bottom: 10px;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .tips-modal .swal2-close {
        color: #888;
        text-shadow: none;
        opacity: 0.7;
    }

    .tips-modal .swal2-close:hover {
        color: #fff;
        opacity: 1;
    }
`;
document.head.appendChild(style);

// Function to show FAQ using SweetAlert
function showFAQ(componentType) {
    const content = faqContent[componentType];
    if (!content) return;

    Swal.fire({
        title: content.title,
        html: content.content,
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            container: 'tips-modal',
            popup: 'tips-popup',
            content: 'tips-content'
        },
        width: '450px',
        padding: '1.2rem',
        background: '#1e1e1e',
        backdrop: 'rgba(0,0,0,0.5)'
    });
}

// Function to sync selected components between desktop and mobile views
function syncSelectedComponents() {
    const desktopComponents = document.getElementById('selected-components');
    const mobileComponents = document.getElementById('selected-components-mobile');
    const desktopTdp = document.getElementById('total_tdp');
    const mobileTdp = document.getElementById('total_tdp_mobile');

    // Sync components
    if (desktopComponents && mobileComponents) {
        mobileComponents.innerHTML = desktopComponents.innerHTML;
        
        // Sync buttons functionality
        const desktopButtons = desktopComponents.querySelectorAll('button');
        const mobileButtons = mobileComponents.querySelectorAll('button');
        
        mobileButtons.forEach((btn, index) => {
            btn.onclick = () => desktopButtons[index].click();
        });
    }

    // Sync TDP
    if (desktopTdp && mobileTdp) {
        mobileTdp.value = desktopTdp.value;
    }

    // Sync publish button visibility
    const desktopPublishBtn = document.querySelector('.selected-components .btn-publish');
    const mobilePublishBtn = document.querySelector('.offcanvas .btn-publish');
    if (desktopPublishBtn && mobilePublishBtn) {
        mobilePublishBtn.style.display = desktopPublishBtn.style.display;
    }
}

// Listen for changes in selected components
const observer = new MutationObserver(syncSelectedComponents);
const desktopComponents = document.getElementById('selected-components');
if (desktopComponents) {
    observer.observe(desktopComponents, { childList: true, subtree: true });
}

// Initial sync
document.addEventListener('DOMContentLoaded', syncSelectedComponents);
