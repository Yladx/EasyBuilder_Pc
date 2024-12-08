<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <style>
        /* Ensure layout fits within viewport minus header */
        .main-layout {
            height: calc(100vh - 78px); /* Adjust height excluding header */
            overflow: hidden; /* Prevent scrolling on the main layout */
        }

        /* Offcanvas positioning */
        .offcanvas {
            top: 78px !important; /* Match header height */
            height: calc(100vh - 78px) !important; /* Adjust height to prevent overlap */
        }

        /* Adjust offcanvas body to ensure proper scrolling */
        .offcanvas-body {
            height: calc(100% - 65px); /* Account for header and footer */
            overflow-y: auto;
        }

        /* Keep floating button above offcanvas but below header */
        .floating-cart-btn {
            bottom: 1rem !important;
            z-index: 1045;
        }

        .h-100 {
            height: 100%; /* Ensure full height for columns */
        }

        .scrollable {
            overflow-y: auto; /* Enable scrolling for content */
        }

        /* Component section transitions */
        #motherboard-section,
        #cpu-section,
        #gpu-section,
        #ram-section,
        #storage-section,
        #psu-section,
        #case-section {
            transition: all 0.3s ease-in-out;
        }

        /* Sidebar styling */
        .sidebar {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        .list-group-item {
            border: none;
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: default;
            pointer-events: none;
        }



        .list-group-item.active {
            background-color: #282828c6;
            border-color: #000000;
        }

        .list-group-item i {
            font-size: 24px;
            margin-right: 10px;
        }






/* Individual Component Item */
.component-item {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 20px; /* Space between image and details */
    padding: 15px;
    border: 1px solid #ddd; /* Light gray border */
    border-radius: 8px;
    background-color: #f9f9f9; /* Subtle background color */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Soft shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover Effect for Component Item */
.component-item:hover {
    transform: translateY(-5px); /* Lift effect */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
    background-color: #f1f1f1; /* Slightly lighter background */
}

/* Component Image */
.component-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    border: 1px solid #ccc; /* Border around the image */
}

/* Component Details */
.component-item div {
    flex-grow: 1; /* Ensures the details take up available space */
}

.component-item .fw-bold {
    font-size: 1.1rem;
    color: #333; /* Darker text for name */
}

.component-item .text-muted {
    font-size: 0.9rem;
    color: #666; /* Medium gray for descriptions */
}

.component-item .text-info {
    font-size: 0.9rem;
    color: #17a2b8; /* Info blue for RAM Slots or similar */
}

/* View Specs Link */
.component-item a {
    font-size: 0.9rem;
    color: #007bff; /* Link blue */
    text-decoration: none;
    transition: color 0.2s ease;
}

.component-item a:hover {
    color: #0056b3; /* Darker blue on hover */
    text-decoration: underline; /* Underline on hover */
}

/* Select Button */
.component-item .btn {
    font-size: 0.9rem;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.component-item .btn-dark {
    background-color: #333; /* Dark background for contrast */
    color: #fff;
    border: none;
}

.component-item .btn-dark:hover {
    background-color: #555; /* Slightly lighter on hover */
    transform: scale(1.05); /* Slightly larger on hover */
}

/* Responsive Design */
@media (max-width: 500px) {
    .component-item {
        flex-direction: column;
        align-items: center; /* Center the items */
        justify-content: center; /* Center items horizontally */
        text-align: center; /* Center the text content */
        margin: 0 auto; /* Ensure the component-item is centered within its parent container */
    }

    .component-item img {
        margin-bottom: 10px;
    }

    .component-item .btn {
        width: 100%; /* Full width button on smaller screens */
    }
}


        .selected-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .selected-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .selected-components-footer {
            position:sticky;
            bottom: 0;
            left: 0;
            right: 0;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn-clear {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-clear:hover {
            background-color: #c82333;
        }

        .btn-publish {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-publish:hover {
            background-color: #0056b3;
        }


        /* Add styles for dragging feedback */
        .floating-cart-btn.dragging {
            opacity: 0.8;
            cursor: grabbing;
        }

        .sticky-header {
    position: sticky;
    top: 0; /* Sticks to the top of its container */
    background-color: white; /* Optional: Matches the background */
    z-index: 1; /* Ensures it stays above other elements */
    padding: 20px; /* Adds some spacing */
    border-bottom: 1px solid #dee2e6; /* Optional: Adds a separator line */
}

    /* Sticky positioning for the continue button */
    .continue-btn {
        position: sticky;
        bottom: 0;
        right: 0;
        margin: 10px;
        z-index: 10;
    }

    #tooltip {
            position: fixed;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            line-height: 1.5;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            pointer-events: none;
            backdrop-filter: blur(5px);
        }

        #tooltip.show {
            opacity: 1;
        }

        #tooltip strong {
            color: #ffffff;
            margin-right: 5px;
        }
    </style>

    <div class="main-layout d-flex p-md-4 p-xs-0 p-0">
        <!-- Sidebar -->
        <div class=" col-2 col-xl-3 h-100 px-0 px-md-3 pt-0 pt-md-3">
    <div class="sidebar h-100 d-flex flex-column scrollable">
          <div class="list-group flex-grow-1">
            <a href="#" class="list-group-item list-group-item-action active" data-component="motherboard">
                <img src="{{ asset('icons/mbs.svg') }}" alt="Motherboard Icon">
<span class="d-none d-md-inline">Motherboard</span> <!-- Text visible only on MD+ -->
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-2" data-component="cpu">
                <img src="{{ asset('icons/cpu.svg') }}" class="mr-2">
                <span class="d-none d-md-inline">CPU</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-2" data-component="gpu">
                <img src="{{ asset('icons/gpu.svg') }}" class="mr-2">
                <span class="d-none d-xl-inline">Graphics Card</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-2" data-component="ram">
                <img src="{{ asset('icons/ram.svg') }}" class="mr-2">
                <span class="d-none d-xl-inline">RAM</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-2" data-component="storage">
                <img src="{{ asset('icons/stg.svg') }}" class="mr-2">
                <span class="d-none d-xl-inline">Storage</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-2" data-component="powerSupply">
                <img src="{{ asset('icons/psu.svg') }}" class="mr-2">
                <span class="d-none d-xl-inline">Power Supply</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action mb-3 mb-xl-3" data-component="case">
                <img  src="{{ asset('icons/case.svg') }}" class="mr-2">
                <span class="d-none d-xl-inline">PC Case</span>
            </a>
        </div>
    </div>
</div>


        <!-- Component Selector -->
        <div class="col-xl-5 col-10 h-100   px-2 px-md-4 pt-3 ">
            <div class="component-selector border rounded-4 h-100 d-flex flex-column scrollable">
                <div id="motherboard-section">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                    <h4 class="text-center mb-0">Select Motherboard</h4>
                    <button
                        class="btn btn-link p-0 text-secondary"
                        onclick="showFAQ('motherboard')"
                        title="Click for FAQ or Guide">
                        <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                    </button>

                </div>



                    <div class="motherboard-components px-4 mt-4">
                        @foreach($motherboards as $motherboard)
                        <div class="component-item flex-shrink-0 mb-3"
                            data-tdp="{{ $motherboard->tdp ?? 0 }}"
                            data-ram-slots="{{ $motherboard->ram_slots ?? 4 }}">
                            <img src="{{ asset('storage/' . ($motherboard->image ?? 'placeholder.png')) }}" alt="{{ $motherboard->name }}">
                            <div>
                                <span class="d-block fw-bold">{{ $motherboard->name }}</span>
                                 <span class="d-block text-muted ">RAM Slots: {{ $motherboard->ram_slots ?? 0 }} Socket: {{ $motherboard->socket ?? N/A }} Socket: {{ $motherboard->socket ?? N/A }}</span>
                                <span>
                           <a class="btn btn-link text-dark p-0"
                               href="javascript:void(0);"  data-tooltip-content="
                                @php
                                $filteredColumns = array_filter($motherboard->getFillable(), function($column) {
                                    $excludedFields = ['id', 'image', 'created_at', 'updated_at', 'name'];
                                    return !in_array($column, $excludedFields);
                                });
                                @endphp
                                @foreach ($filteredColumns as $column)
                                    <strong>{{ ucfirst(str_replace('_', ' ', $column)) }}</strong>: {{ $motherboard->$column ?? 'N/A' }}<br>
                                @endforeach
                            "><i class="fas fa-info-circle"></i></a>
                </span>
                            </div>
                            <button class="btn btn-dark btn-sm select-component"
                                data-component-type="motherboard"
                                data-component-id="{{ $motherboard->id }}">
                                Select
                            </button>
                        </div>
                        @endforeach
                    </div>

                </div>

                <div id="cpu-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select CPU</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('cpu')"
                    title="Click for CPU Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="cpu-components px-4 mt-4"></div>
                </div>

                <div id="gpu-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select Graphics Card</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('gpu')"
                    title="Click for GPU Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="gpu-components px-4 mt-4"></div>
                </div>

                <div id="ram-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select Ram</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('ram')"
                    title="Click for RAM Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="ram-components px-4 mt-4"></div>
                </div>

                <div id="storage-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select Storage</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('storage')"
                    title="Click for Storage Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="storage-components px-4 mt-4"></div>
                </div>

                <div id="psu-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select Power Supply</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('powerSupply')"
                    title="Click for PSU Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="psu-components px-4 mt-4"></div>
                </div>

                <div id="case-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center sticky-header">
                <h4 class="text-center mb-0">Select Computer Case</h4>
                <button
                    class="btn btn-link p-0 text-secondary"
                    onclick="showFAQ('case')"
                    title="Click for Case Tips">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </button>
            </div>

                    <div class="case-components px-4 mt-4"></div>
                </div>
            </div>


    <!-- Tooltip Container -->
    <div id="tooltip" style="display: none; position: absolute; z-index: 9999; background: rgba(0, 0, 0, 0.8); color: #fff; padding: 10px; border-radius: 5px; font-size: 0.9rem; max-width: 300px; pointer-events: none;"></div>



        </div>

        <!-- Selected Components -->
        <div class="col-sm-4 d-none d-xl-block h-100 px-3 pt-3">
            <div class="border rounded-4  h-100 d-flex flex-column  scrollable ">
            <div class="d-flex justify-content-between align-items-center sticky-header">
            <h4>Selected Components</h4>
                    <div class="tdp-calculator">
                        <div class="d-flex align-items-center">

                            <input type="text"
                                   class="form-control text-center fw-bold"
                                   id="total_tdp"
                                   value="0 W"
                                   readonly
                                   style="width: 100px;">
                        </div>
                    </div>
                </div>

                <div id="selected-components" class=" px-4 mt-4"></div>
                <div class="mt-auto bg-light selected-components-footer px-5 py-2" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <button class="btn btn-black btn-publish" style="display: none; background-color: black; color: white;">Save Build</button>
                    <button class="btn btn-clear" style="background-color: white; color: black; border: 1px solid black; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">Clear All</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Selected Components Offcanvas for small screens -->
    <div class="offcanvas offcanvas-light offcanvas-end bg-white text-dark" tabindex="-1" id="selectedComponentsOffcanvas" aria-labelledby="selectedComponentsOffcanvasLabel" style="background-color: white !important;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="selectedComponentsOffcanvasLabel">Selected Components</h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <span class="me-2">Total TDP:</span>
                    <input type="text"
                           class="form-control text-center fw-bold"
                           id="total_tdp_mobile"
                           value="0 W"
                           readonly
                           style="width: 100px;">
                </div>
            </div>
            <!-- Container for selected components that mirrors desktop view -->
            <div id="selected-components-mobile" class="flex-grow-1 overflow-auto px-3"></div>
            <div class="mt-auto p-3 border-top">
                <button class="btn btn-black btn-publish w-100 mb-2" style="display: none; background-color: black; color: white;">Save Build</button>
                <button class="btn btn-clear w-100" style="background-color: white; color: black; border: 1px solid black; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">Clear All</button>
            </div>
        </div>
    </div>

    <!-- Add floating button for small screens -->
    <button
            class=" border-0 bg-none bg-transparent position-fixed end-0 top-50 translate-middle-y m-3 d-xl-none floating-cart-btn"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#selectedComponentsOffcanvas"
            aria-controls="selectedComponentsOffcanvas"
>  <box-icon name='chevrons-left' animation='burst' ></box-icon>
    </button>

   <!-- Instructions Modal -->
   <div class="modal fade" id="instructionsModal" tabindex="-1" aria-labelledby="instructionsModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
           <div class="modal-content ">
               <div class="modal-header border-bottom border-secondary">
                   <h5 class="modal-title" id="instructionsModalLabel">How to Build Your PC</h5>
                   <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body p-5">
                   <div class="instructions-container">
                       <h6 class="mb-3">Follow these steps to build your perfect PC:</h6>

                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">1. Start with the Motherboard</h6>
                           <p>Choose your motherboard first as it determines compatibility with other components.</p>
                       </div>
                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">2. Add CPU and RAM</h6>
                           <p>Select a compatible CPU and RAM that matches your motherboard's specifications.</p>
                       </div>
                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">3. Choose Graphics Card</h6>
                           <p>Pick a graphics card that suits your needs and ensures your power supply can handle it.</p>
                       </div>
                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">4. Add Storage</h6>
                           <p>Select your storage devices (SSD/HDD) based on your capacity and speed needs.</p>
                       </div>
                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">5. Power Supply</h6>
                           <p>Choose a power supply that can handle your total system power requirements.</p>
                       </div>
                       <div class="instruction-step mb-3">
                           <h6 class="text-primary">6. Select Case</h6>
                           <p>Pick a case that fits your motherboard form factor and has space for all components.</p>
                       </div>
                       <div class="alert alert-info">
                           <i class="fas fa-info-circle me-2"></i>
                           Watch for compatibility warnings at each step. They'll help ensure all your parts work together!
                           <hr class="my-2">
                           <i class="fas fa-exclamation-circle me-2"></i>
                           <strong>Important:</strong> Once you select a component, you cannot go back to change it. To modify your selection, use the "Clear All" button and start over.
                       </div>
                   </div>
               </div>
               <div class="modal-footer border-top border-secondary">
                   <div class="form-check me-auto">
                       <input class="form-check-input" type="checkbox" id="dontShowAgain">
                       <label class="form-check-label " for="dontShowAgain">
                           Don't show this again
                       </label>
                   </div>
                   <button type="button" class="easypc-btn" data-bs-dismiss="modal">Got it!</button>
               </div>
           </div>
       </div>
   </div>

   <script>
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
   </script>

<script>

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

</script>
 <!-- Add SweetAlert and JavaScript -->
 <script src="{{ asset('js/buildpc-faq.js') }}"></script>

 <!-- Add JavaScript for component selection -->
 <script src="{{ asset('js/buildpc-utils.js') }}"></script>
 <script src="{{ asset('js/buildpc-components.js') }}"></script>
 <script src="{{ asset('js/buildpc-modal.js') }}"></script>

 <script src="{{ asset('js/buildpccompatability-user.js') }}"></script>



</x-app-layout>
