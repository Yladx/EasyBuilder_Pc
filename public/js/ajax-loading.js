// Global loading counter
let loadingCounter = 0;

// Show loading animation
function showLoading() {
    loadingCounter++;
    if (loadingCounter === 1) {
        Swal.fire({
            html: `
                <div class="loading-container">
                    <div class="loading-spinner">
                        <div class="loading-segment" style="--segment-delay: 0"></div>
                        <div class="loading-segment" style="--segment-delay: 1"></div>
                        <div class="loading-segment" style="--segment-delay: 2"></div>
                        <div class="loading-segment" style="--segment-delay: 3"></div>
                    </div>
                    <div class="loading-text">Loading...</div>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'loading-popup'
            }
        });
    }
}

// Hide loading animation
function hideLoading() {
    loadingCounter--;
    if (loadingCounter <= 0) {
        loadingCounter = 0;
        Swal.close();
    }
}

// Handle AJAX requests
$(document).ajaxStart(function() {
    showLoading();
});

$(document).ajaxStop(function() {
    hideLoading();
});

// Handle Fetch requests
const originalFetch = window.fetch;
window.fetch = function() {
    showLoading();
    return originalFetch.apply(this, arguments)
        .then(response => {
            hideLoading();
            return response;
        })
        .catch(error => {
            hideLoading();
            throw error;
        });
};

// Handle form submissions
document.addEventListener('submit', showLoading);

// Handle navigation links (excluding anchors and downloads)
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && !e.target.hasAttribute('download') && e.target.href && e.target.href.indexOf('#') === -1) {
        showLoading();
    }
});
