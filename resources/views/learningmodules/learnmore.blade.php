<x-app-layout>
    <div class="container-fluid">
        <!-- Toggle Button -->
        <button id="toggleSidebar">
            <i class="fa fa-close"></i>
        </button>

        <!-- Sidebar -->
        <div class="learningmodules-sidebar" id="sidebar">
            <div class="sidebar-header">
                Learning Modules
            </div>
            <ul class="nav">
                <li style="width: 100%">
                    <a href="#" id="introductionLink">Introduction</a>
                </li>
                <li style="width: 100%">
                    <a href="#dropdownModules" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Learning Modules
                    </a>
                    <ul class="collapse list-unstyled me-5" id="dropdownModules">
                        @foreach ($modulesByTag as $tag => $modules)
                            <li>
                                <a href="#{{ Str::slug($tag) }}" class="module-group" data-bs-toggle="collapse" aria-expanded="false">
                                    {{ $tag }}
                                </a>
                                <ul class="collapse list-unstyled module-submenu" id="{{ Str::slug($tag) }}">
                                    @foreach ($modules as $module)
                                        <li>
                                            <a href="#" class="module-title" data-id="{{ $module->id }}">
                                                {{ $module->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>

            </ul>
        </div>

        <!-- Main Content -->
        <div class="learningmodules-content p-5" id="mainContent">
           @if($selectedModule)
                <h2 style="font-size: 2rem; color: #333; margin-bottom: 20px;">{{ $selectedModule->title }}</h2>
                <hr>
                <div class="description mt-4">
                    <p style="font-size: 1rem; color: #555;">{{ $selectedModule->description }}</p>
                </div>
                <div class="video text-center mt-4">
                    <video width="560" height="315" controls fullscreen style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <source src="/storage/{{ $selectedModule->video_src }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="text-content mt-5 text-al fs-6 lh-base text-justify" style="color: #333;">
                    <p>{!! $selectedModule->Information !!}</p>
                </div>
           @else
                @include('learningmodules.partials.introduction')
           @endif
        </div>

        <!-- Scripts -->
        <script>
            const toggleSidebar = document.getElementById('toggleSidebar');
            const body = document.body;
            const icon = toggleSidebar.querySelector('i');

            // Sidebar toggle functionality
            toggleSidebar.addEventListener('click', function () {
                body.classList.toggle('sidebar-collapsed');
                if (body.classList.contains('sidebar-collapsed')) {
                    icon.classList.remove('fa-close');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-close');
                }
            });

            // Click event on module titles
            const moduleTitles = document.querySelectorAll('.module-title');
            const mainContent = document.getElementById('mainContent');
            const introductionLink = document.getElementById('introductionLink');
            const introductionContent = document.querySelector('.learningmodules-content').innerHTML;

            moduleTitles.forEach(function (title) {
                title.addEventListener('click', function (e) {
                    e.preventDefault();
                    const moduleId = this.getAttribute('data-id');
                    // Update URL without reloading the page
                    window.history.pushState({}, '', `/learning-modules/${moduleId}`);
                    loadModuleContent(moduleId);
                });
            });

            // Function to load the module content dynamically
            function loadModuleContent(moduleId) {
                fetch(`/learningmodules/fetch/${moduleId}`)
                    .then(response => response.json())
                    .then(module => {
                        mainContent.innerHTML = `
                            <h2 style="font-size: 2rem; color: #333; margin-bottom: 20px;">${module.title}</h2>
                            <hr>
                            <div class="description mt-4">
                                <p style="font-size: 1rem; color: #555;">${module.description}</p>
                            </div>
                            <div class="video text-center mt-4">
                                <video width="560" height="315" controls fullscreen style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                    <source src="/storage/${module.video_src}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="text-content mt-5 text-al fs-6 lh-base text-justify" style="color: #333;">
                                <p>${module.Information}</p>
                            </div>
                        `;
                    })
                    .catch(error => console.error('Error loading module content:', error));
            }

            // Event listener for the "Introduction" link
            introductionLink.addEventListener('click', function (e) {
                e.preventDefault();
                window.history.pushState({}, '', '/learning-modules');
                mainContent.innerHTML = introductionContent;
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Get all dropdowns
                const dropdowns = document.querySelectorAll('[data-bs-toggle="collapse"]');
                
                // Add click handler to each dropdown
                dropdowns.forEach(dropdown => {
                    dropdown.addEventListener('click', function() {
                        // Toggle the clicked dropdown's active state
                        this.classList.toggle('active');
                        
                        // Get the target collapse element
                        const targetId = this.getAttribute('href') || this.getAttribute('data-bs-target');
                        const targetCollapse = document.querySelector(targetId);
                        
                        // If this dropdown is being opened, close other dropdowns at the same level
                        if (!targetCollapse.classList.contains('show')) {
                            const parent = this.closest('ul');
                            const siblings = parent.querySelectorAll('[data-bs-toggle="collapse"]');
                            siblings.forEach(sibling => {
                                if (sibling !== this) {
                                    // Remove active class from other dropdowns
                                    sibling.classList.remove('active');
                                    // Get and close their target collapse elements
                                    const siblingTargetId = sibling.getAttribute('href') || sibling.getAttribute('data-bs-target');
                                    const siblingCollapse = document.querySelector(siblingTargetId);
                                    if (siblingCollapse && siblingCollapse.classList.contains('show')) {
                                        siblingCollapse.classList.remove('show');
                                    }
                                }
                            });
                        }
                    });
                });

                // If there's a selected module, expand its parent dropdowns
                @if($selectedModule)
                    const moduleLink = document.querySelector(`[data-id="{{ $selectedModule->id }}"]`);
                    if (moduleLink) {
                        // Find and show parent dropdowns
                        let parent = moduleLink.closest('.collapse');
                        while (parent) {
                            parent.classList.add('show');
                            const trigger = document.querySelector(`[href="#${parent.id}"], [data-bs-target="#${parent.id}"]`);
                            if (trigger) {
                                trigger.classList.add('active');
                            }
                            parent = parent.parentElement.closest('.collapse');
                        }
                    }
                @endif
            });
        </script>

    </div>
</x-app-layout>
<style>
       /* Description Section Styles */
       .description {
            background: linear-gradient(135deg, #a8acb3, #9b9ea2);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #ccd6dd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: relative;
        }

        .description:before {
            content: "Description";
            position: absolute;
            top: -15px;
            left: 20px;
            background: #686c70;
            color: #fff;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .description p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }


.video {
            text-align: center;
            margin: 20px 0;
        }

        .video video {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%; /* Makes the video responsive to container width */
            max-width: 720px; /* Sets the maximum width for the video */
            height: auto; /* Maintains the aspect ratio */
        }




</style>
