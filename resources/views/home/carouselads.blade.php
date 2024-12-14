<section class="carousel">
    <div id="carouselExample" class="carousel carousel-dark slide" data-bs-ride="carousel" data-bs-interval="3000">
        <style>
            .carousel-item {
                transition: transform 0s ease, opacity 0s ease;
            }
            .carousel-item.active {
                transform: translateX(0);
            }
            .carousel-item a {
                cursor: pointer;
            }
            .carousel-caption {
                z-index: 2;
            }

            /* Responsive carousel heights */
            #carouselExample {
                height: 87vh;
            }

            /* For tablets and smaller laptops */
            @media (max-width: 1024px) {
                #carouselExample {
                    height: 70vh;
                }
                .carousel-caption {
                    bottom: 40px !important;
                }
                .carousel-caption h5 {
                    font-size: 1.5rem !important;
                }
            }

            /* For mobile devices */
            @media (max-width: 768px) {
                #carouselExample {
                    height: 50vh;
                }
                .carousel-caption {
                    bottom: 20px !important;
                    padding-left: 1rem !important;
                }
                .carousel-caption h5 {
                    font-size: 1.2rem !important;
                }
                .carousel-caption p {
                    font-size: 0.8rem !important;
                }
                .carousel-caption .btn {
                    font-size: 0.9rem !important;
                    padding: 0.375rem 0.75rem !important;
                }
            }

            /* For very small devices */
            @media (max-width: 480px) {
                #carouselExample {
                    height: 40vh;
                }
                .carousel-caption {
                    bottom: 10px !important;
                }
                .carousel-caption h5 {
                    font-size: 1rem !important;
                    margin-bottom: 0.25rem !important;
                }
                .carousel-caption p {
                    font-size: 0.7rem !important;
                    margin-bottom: 0.5rem !important;
                }
            }
        </style>

      <!-- Carousel Indicators -->
      <div class="carousel-indicators">
          <!-- Static Slide Indicator -->
          <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Static Slide"></button>
          
          @foreach ($advertisements as $key => $ad)
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $key + 1 }}"
                  aria-label="Slide {{ $key + 2 }}"></button>
          @endforeach
      </div>

        <!-- Carousel Items -->
        <div class="carousel-inner h-100">

            <!-- Static Slide -->
            <div class="carousel-item active bg-black" data-static-slide="true">
                <img src="{{ asset('logo.svg') }}" class="d-block w-100 h-100" style="object-fit: contain; opacity: 0.7;" alt="PC Build 1">
                <div class="carousel-caption d-flex flex-column justify-content-start align-items-start text-start ps-5" style="bottom: 70px; top: auto;">
                    <h5 class="text-white" style="font-size: 2rem; font-weight: bold;">Build Your PC Now</h5>
                    <span class="mt-3 text-white" style="font-size: 1.2rem;">
                        Explore our features to build the perfect PC for your needs.
                    </span>
                    <a class="btn btn-light mt-3" style="font-size: 1rem; font-weight: bold;" href="{{ route('build.build-pc') }}">
                        Join Us & Build Your PC Now
                    </a>
                </div>
            </div>

            <!-- Dynamic Slides -->
            @foreach ($advertisements as $key => $ad)
                <div class="carousel-item h-100" style="position: relative;">
                    @if($ad->access_link)
                    <a href="{{ $ad->access_link }}" target="_blank" class="text-decoration-none d-block h-100">
                    @endif
                    @if ($ad->type === 'image' && $ad->src)
                        <img src="{{ asset('storage/' . $ad->src) }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="{{ $ad->label }}">
                    @elseif ($ad->type === 'video' && $ad->src)
                        <video class="d-block w-100 h-100" autoplay muted loop playsinline style="object-fit: cover;">
                            <source src="{{ asset('storage/' . $ad->src) }}" type="video/mp4">
                        </video>
                    @endif

                    <!-- Caption -->
                    <div class="carousel-caption d-flex flex-column justify-content-start align-items-start text-start ps-5" style="bottom: 70px; top: auto; pointer-events: none;">
                        <h5 class="text-white">{{ $ad->label }}</h5>
                        <p class="text-white" style="{{ !$ad->access_link ? 'font-size: 1rem;' : 'font-size: 1.2rem;' }}">
                            {{ $ad->caption }}
                        </p>
                    </div>
                    @if($ad->access_link)
                    </a>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev no-padding-control d-none" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next no-padding-control d-none" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carouselExample');
    const carouselInstance = new bootstrap.Carousel(carousel);
    
    // Auto-next after 1 minute for the static slide
    setTimeout(() => {
        carouselInstance.next();
    }, 60000); // 60000 milliseconds = 1 minute
});
</script>
