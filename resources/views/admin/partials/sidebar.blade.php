<div class="sidebar sidebar-no-transition" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-wrapper">
            <div class="brand-logo">
                <img src="{{ asset('assets/img/logo-hk.png') }}" alt="HK Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text">
                <span class="brand-name">HK <span class="text-danger">ADMIN</span></span>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-label">UTAMA</div>
        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
            <i class="bi bi-speedometer2"></i> <span class="link-text">Dashboard</span>
        </a>

        <div class="nav-label">INVENTARIS</div>
        <a class="nav-link {{ Request::is('admin/equipment*') ? 'active' : '' }}" href="{{ url('admin/equipment') }}">
            <i class="bi bi-gear-wide-connected"></i> <span class="link-text">Alat Berat</span>
        </a>
        <a class="nav-link {{ Request::is('admin/customer*') ? 'active' : '' }}" href="{{ url('admin/customer') }}">
            <i class="bi bi-people-fill"></i> <span class="link-text">Pelanggan</span>
        </a>

        <div class="nav-label">OPERASIONAL</div>
        <a class="nav-link {{ Request::is('admin/schedule*') ? 'active' : '' }}" href="{{ route('admin.schedule.index') }}">
            <i class="bi bi-calendar3-range"></i> <span class="link-text">Jadwal Sewa</span>
        </a>
        <a class="nav-link {{ Request::is('admin/rentals*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}">
            <i class="bi bi-file-earmark-medical"></i> <span class="link-text">Transaksi</span>
        </a>
        <a class="nav-link {{ Request::is('admin/overtime*') ? 'active' : '' }}" href="{{ route('admin.overtime.index') }}">
            <i class="bi bi-lightning-charge"></i> <span class="link-text">Overtime (Lembur)</span>
        </a>

        <div class="nav-label">INTERAKSI</div>
        <a class="nav-link {{ Request::is('admin/testimonis*') ? 'active' : '' }}" href="{{ route('admin.testimonis.index') }}">
            <i class="bi bi-chat-left-heart-fill"></i> <span class="link-text">Testimoni Klien</span>
        </a>

        <div class="nav-label">FINANSIAL</div>
        <a class="nav-link {{ Request::is('admin/finance*') ? 'active' : '' }}" href="{{ route('admin.finance.index') }}">
            <i class="bi bi-wallet2"></i> <span class="link-text">Keuangan (Kas)</span>
        </a>

       

        <div class="nav-label">AKUN</div>
        <a class="nav-link {{ Request::is('admin/profile*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
        <i class="bi bi-person-circle"></i> <span class="link-text">Edit Profil</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="button" onclick="confirmLogout()" class="nav-link btn-logout-custom">
                <i class="bi bi-power"></i> <span class="link-text">Logout</span>
            </button>
        </form>
    </nav>

    <div class="sidebar-footer">
        <button id="sidebarToggle" class="toggle-btn">
            <i class="bi bi-arrow-left-right"></i>
        </button>
    </div>
</div>