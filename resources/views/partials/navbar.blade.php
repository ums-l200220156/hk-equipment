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
                <div class="dropdown">
                    <a class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.rentals') }}">
                                <i class="bi bi-clock-history me-2"></i> Riwayat Sewa
                            </a>
                        </li>
                        <li><hr></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>
