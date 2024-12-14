// Show instructions modal on page load if not dismissed before
document.addEventListener('DOMContentLoaded', function() {
    const existingDOMContentLoadedHandler = window.onDOMContentLoaded;

    // First run the existing DOMContentLoaded handler if it exists
    if (typeof existingDOMContentLoadedHandler === 'function') {
        existingDOMContentLoadedHandler();
    }

    // Then handle the instructions modal
    if (!localStorage.getItem('hideInstructions')) {
        const instructionsModal = new bootstrap.Modal(document.getElementById('instructionsModal'));
        instructionsModal.show();
    }

    // Handle the "Don't show again" checkbox
    document.getElementById('dontShowAgain').addEventListener('change', function(e) {
        if (e.target.checked) {
            localStorage.setItem('hideInstructions', 'true');
        } else {
            localStorage.removeItem('hideInstructions');
        }
    });
});


function showTooltip(event, content) {
 const tooltip = document.getElementById('tooltip');
 tooltip.innerHTML = content;
 tooltip.classList.add('show');
 moveTooltip(event); // Position the tooltip
}

function hideTooltip() {
 const tooltip = document.getElementById('tooltip');
 tooltip.classList.remove('show');
 setTimeout(() => {
     tooltip.style.display = 'none';
 }, 300); // Match the CSS transition time
}

function moveTooltip(event) {
 const tooltip = document.getElementById('tooltip');
 const tooltipWidth = tooltip.offsetWidth;
 const tooltipHeight = tooltip.offsetHeight;

 const xOffset = 15; // Offset from the mouse pointer
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
}

document.addEventListener('DOMContentLoaded', function() {
 const viewSpecsLinks = document.querySelectorAll('.component-item a');

 viewSpecsLinks.forEach(link => {
     // Show tooltip on hover
     link.addEventListener('mouseenter', function(event) {
         const tooltip = document.getElementById('tooltip');
         tooltip.innerHTML = this.dataset.tooltipContent;
         tooltip.style.display = 'block';
         tooltip.classList.add('show');
         moveTooltip(event);
     });

     link.addEventListener('mouseleave', function() {
         const tooltip = document.getElementById('tooltip');
         tooltip.classList.remove('show');
         setTimeout(() => {
             tooltip.style.display = 'none';
         }, 300);
     });

     // Show tooltip on click
     link.addEventListener('click', function(event) {
         event.preventDefault(); // Prevent default link behavior and loading animation
         event.stopPropagation(); // Stop event from propagating to parent elements
         const tooltip = document.getElementById('tooltip');
         tooltip.innerHTML = this.dataset.tooltipContent;
         tooltip.style.display = 'block';
         tooltip.classList.add('show');
         moveTooltip(event);
     });
 });
});

document.addEventListener('DOMContentLoaded', function() {
 const button = document.querySelector('.floating-cart-btn');
 let touchstartX = 0;
 let touchendX = 0;

 function handleGesture() {
     if (touchendX < touchstartX) {
         // Swipe left detected
         const offcanvas = new bootstrap.Offcanvas(document.getElementById('selectedComponentsOffcanvas'));
         offcanvas.show();
     }
 }

 button.addEventListener('touchstart', function(event) {
     touchstartX = event.changedTouches[0].screenX;
 });

 button.addEventListener('touchend', function(event) {
     touchendX = event.changedTouches[0].screenX;
     handleGesture();
 });
});

document.addEventListener('DOMContentLoaded', function() {
 const button = document.querySelector('.floating-cart-btn');
 let isDragging = false;
 let startX, startY, initialX, initialY;

 button.addEventListener('mousedown', function(e) {
     isDragging = true;
     startX = e.clientX;
     startY = e.clientY;
     initialX = button.offsetLeft;
     initialY = button.offsetTop;
     button.classList.add('dragging');
 });

 document.addEventListener('mousemove', function(e) {
     if (isDragging) {
         const dx = e.clientX - startX;
         const dy = e.clientY - startY;
         button.style.left = initialX + dx + 'px';
         button.style.top = initialY + dy + 'px';
     }
 });

 document.addEventListener('mouseup', function() {
     if (isDragging) {
         isDragging = false;
         button.classList.remove('dragging');
         // Reset to original position
         button.style.left = '';
         button.style.top = '';
     }
 });
});

document.addEventListener('DOMContentLoaded', function() {
 const offcanvas = document.getElementById('selectedComponentsOffcanvas');
 const floatingCartBtn = document.querySelector('.floating-cart-btn');

 offcanvas.addEventListener('show.bs.offcanvas', function() {
     floatingCartBtn.style.display = 'none';
 });

 offcanvas.addEventListener('hidden.bs.offcanvas', function() {
     floatingCartBtn.style.display = 'block';
 });
});

document.addEventListener('DOMContentLoaded', function() {
 // Function to sync selected components between desktop and mobile views
 function syncSelectedComponents() {
     const desktopComponents = document.getElementById('selected-components');
     const mobileComponents = document.getElementById('selected-components-mobile');

     if (desktopComponents && mobileComponents) {
         // Clone the desktop content to mobile view
         mobileComponents.innerHTML = desktopComponents.innerHTML;

         // Sync TDP values
         const desktopTdp = document.getElementById('total_tdp');
         const mobileTdp = document.getElementById('total_tdp_mobile');
         if (desktopTdp && mobileTdp) {
             mobileTdp.value = desktopTdp.value;
         }

         // Sync publish button visibility
         const desktopPublish = document.querySelector('.col-sm-4 .btn-publish');
         const mobilePublish = document.querySelector('#selectedComponentsOffcanvas .btn-publish');
         if (desktopPublish && mobilePublish) {
             mobilePublish.style.display = desktopPublish.style.display;
         }
     }
 }

 // Create a MutationObserver to watch for changes in the desktop view
 const observer = new MutationObserver(syncSelectedComponents);
 const desktopComponents = document.getElementById('selected-components');

 if (desktopComponents) {
     observer.observe(desktopComponents, {
         childList: true,
         subtree: true,
         characterData: true
     });
 }

 // Initial sync
 syncSelectedComponents();

 // Sync when offcanvas is shown
 const offcanvas = document.getElementById('selectedComponentsOffcanvas');
 if (offcanvas) {
     offcanvas.addEventListener('show.bs.offcanvas', syncSelectedComponents);
 }
});