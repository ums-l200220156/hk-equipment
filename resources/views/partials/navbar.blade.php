<nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom sticky-top shadow-lg">
    <div class="container">
        <a class="navbar-brand text-danger" href="#home">
            HK EQUIPMENT <span class="text-accent">🚧</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                @foreach([
                    'home' => 'Beranda',
                    'keunggulan' => 'Keunggulan',
                    'alat' => 'Armada',
                    'proses' => 'Sewa',
                    'testimoni' => 'Testimoni',
                    'maps' => 'Lokasi'
                ] as $id => $label)
                <li class="nav-item">
                    <a class="nav-link nav-scroll" href="{{ url('/') }}#{{ $id }}" data-target="{{ $id }}">
                        {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-danger">Register</a>
            @endguest

            @auth
            <div class="dropdown nav-profile-wrapper">
                <a class="nav-profile-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-content">
                        @if(Auth::user()->image)
                            <img src="{{ asset('uploads/users/'.Auth::user()->image) }}" alt="User" class="nav-avatar">
                        @else
                            <div class="nav-avatar-placeholder">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        @endif
                        <div class="profile-text">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-email d-lg-none">{{ Auth::user()->email }}</span>
                            <span class="user-status"><i class="bi bi-circle-fill"></i> Online</span>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end custom-dropdown-animate shadow-lg">
                    <li class="dropdown-header-box d-lg-none">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('customer.profile') }}">
                            <i class="bi bi-person-badge"></i>
                            <span>Profil Lengkap</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('customer.rentals') }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Sewa</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="button" class="dropdown-item text-danger fw-bold" onclick="confirmLogout()">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
        </div>
    </div>
</nav>
