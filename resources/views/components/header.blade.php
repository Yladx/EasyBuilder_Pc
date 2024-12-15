@auth
<!-- Header for Logged-In Users -->
<header>
    <nav class="navbar navbar-expand-lg text-white" style="padding: 15px;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center text-white" href="{{ route('home') }}">
                <img src="{{ asset('logo.svg') }}" alt="Logo" width="60" height="40" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" onclick="navigateToAbout()">About</a>
                    </li>
                   <!-- Build PC Link -->
                   <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('build.build-pc') ? 'active' : '' }}" href="{{ route('build.build-pc') }}">Build PC</a>
                    </li>

                    <!-- View Build Link -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('builds.display') ? 'active' : '' }}" href="{{ route('builds.display') }}">View Build</a>
                    </li>

                    <!-- Learn More Link -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('learning.modules') ? 'active' : '' }}" href="{{ route('learning.modules') }}">Learn More</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                                <a class="dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile Settings</a>
                            </li>
                <li>
                                <a class="dropdown-item" href="#" onclick="logoutWithConfirmation()">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Navbar -->
    <div class="offcanvas offcanvas-start bg-dark text-white z-index-5" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" >
        <div class="offcanvas-header border-bottom">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fs-4 me-2"></i>
                <h5 class="offcanvas-title text-white mb-0">{{ auth()->user()->name }}</h5>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
            <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" onclick="navigateToAbout()">About</a>
                 <!-- Build PC Link -->
                   <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('build.build-pc') ? 'active' : '' }}" href="{{ route('build.build-pc') }}">Build PC</a>
                    </li>

                    <!-- View Build Link -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('builds.display') ? 'active' : '' }}" href="{{ route('builds.display') }}">View Build</a>
                    </li>

                    <!-- Learn More Link -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('learning.modules') ? 'active' : '' }}" href="{{ route('learning.modules') }}">Learn More</a>
                    </li>
                <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" onclick="logoutWithConfirmation()">Logout</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
@endauth

@guest
<!-- Header for Guest Users -->
<header>
    <nav class="navbar navbar-expand-lg" style="padding: 15px;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center text-white" href="{{ route('home') }}">
                <img src="{{ asset('logo.svg') }}" alt="Logo" width="60" height="40" class="d-inline-block align-text-top">
            </a>   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" onclick="navigateToAbout()">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('builds.display') }}">View Build</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success ms-md-2 ms-xs-1"  href="{{ route('login') }}">Login/SignUp</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
@endguest

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function navigateToAbout() {
        const currentRoute = window.location.pathname;
        const homeRoute = '/';

        if (currentRoute !== homeRoute) {
            window.location.href = `${homeRoute}#aboutus`;
        } else {
            document.getElementById('aboutus').scrollIntoView({ behavior: 'smooth' });
        }
    }

    function logoutWithConfirmation() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out of your account.',
            icon: 'warning',
            showCancelButton: true,
            
  confirmButtonColor: "black",
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
.offcanvas {
    z-index: 9999 !important;
}
.offcanvas-header {
    padding: 1rem;
}
.offcanvas-title {
    font-size: 1.1rem;
    font-weight: 500;
}
.nav-link {
    padding: 0.8rem 1rem;
    transition: background-color 0.2s;
}
.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
}
</style>
