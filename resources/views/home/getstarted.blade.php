
        <section class="letgetstarted w-100 d-flex align-items-center" style="min-height: 100vh; background-color: #000;">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                <!-- Image Section -->
                <div class="image-container">
                <img src="{{ asset('/css/pcbuilds.png') }}" alt="PC Setup" class="rounded">
                </div>

                <!-- Text Section -->
                <div class="text-container text-center text-md-start text-white mt-4 mt-md-0 p-5">
                    <h1 class="mb-4">Welcome to the ultimate PC building experience</h1>
                    <p class="mb-4">
                        Where you can customize, explore, and share your perfect setup.
                    </p>
                    <a href="{{ route('build.build-pc') }}"class="btn btn-light rounded-pill px-4 py-2">
                        Let's get started!
                    </a>
                </div>
            </div>
        </section>
