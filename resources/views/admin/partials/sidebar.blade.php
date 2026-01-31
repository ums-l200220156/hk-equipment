<style>
    /* --- SIDEBAR STYLING --- */
    .sidebar { 
        height: 100vh; 
        width: 260px; 
        position: fixed; 
        top: 0; left: 0; 
        background-color: #212529; 
        color: #fff; 
        padding-top: 20px; 
        z-index: 1000; 
        transition: all 0.3s; 
    }

    .sidebar-header { 
        padding: 0 20px 20px 20px; 
        border-bottom: 1px solid rgba(255,255,255,0.1); 
        margin-bottom: 20px; 
    }

    .brand-name { 
        font-size: 24px; 
        font-weight: 800; 
        color: #dc3545; 
        letter-spacing: 1px; 
    }

    .nav-link { 
        color: rgba(255,255,255,0.7); 
        padding: 12px 20px; 
        font-size: 16px; 
        display: flex; 
        align-items: center; 
        transition: all 0.3s; 
        border-left: 4px solid transparent; 
    }

    .nav-link:hover, 
    .nav-link.active { 
        color: #fff; 
        background-color: rgba(255,255,255,0.05); 
        border-left: 4px solid #dc3545; 
    }

    .nav-link i { 
        margin-right: 12px; 
        font-size: 1.2rem; 
    }

    .sidebar-footer { 
        padding: 15px; 
        border-top: 1px solid rgba(255,255,255,0.15); 
    }

    /* Transisi Halus */
    .sidebar, .main-content, .brand-name, .link-text {
        transition: all 0.3s ease-in-out;
    }

    /* Sidebar Tertutup (Collapsed) */
    body.sidebar-closed .sidebar { width: 80px; }
    body.sidebar-closed .main-content { margin-left: 80px; }

    body.sidebar-closed .link-text,
    body.sidebar-closed .sidebar-header small,
    body.sidebar-closed .sidebar-header .text-uppercase {
        display: none;
    }

    body.sidebar-closed .brand-name {
        font-size: 1.2rem;
        text-align: center;
    }

    body.sidebar-closed .sidebar-header {
        padding: 20px 10px;
        text-align: center;
    }

    body.sidebar-closed .nav-link {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    body.sidebar-closed .nav-link i {
        margin-right: 0;
        font-size: 1.5rem;
    }

    body.sidebar-closed #sidebarToggle i {
        transform: rotate(180deg);
    }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-name">
            <i class="bi bi-cone-striped"></i> HK ADMIN
        </div>
        <small class="text-muted" style="font-size: 12px;">Equipment Management System</small>
    </div>
    
    <nav class="nav flex-column" style="flex: 1; overflow-y: auto;">
        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
            <i class="bi bi-grid-1x2-fill"></i> <span class="link-text">Dashboard</span>
        </a>

        <a class="nav-link {{ Request::is('admin/equipment*') ? 'active' : '' }}" href="{{ url('admin/equipment') }}">
            <i class="bi bi-truck-front-fill"></i> <span class="link-text">Manajemen Alat Berat</span>
        </a>

        <a class="nav-link {{ Request::is('pelanggan*') ? 'active' : '' }}" href="{{ url('admin/customer') }}">
            <i class="bi bi-people-fill"></i> <span class="link-text">Manajemen Pelanggan</span>
        </a>

        <a class="nav-link {{ Request::is('admin/schedule*') ? 'active' : '' }}" href="{{ route('admin.schedule.index') }}">
            <i class="bi bi-calendar-check-fill"></i> <span class="link-text">Kelola Jadwal Sewa</span>
        </a>

        <a class="nav-link {{ Request::is('admin/rentals*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}">
            <i class="bi bi-receipt"></i> <span class="link-text">Kelola Transaksi</span>
        </a>

        <div class="mt-4 px-3 mb-2">
            <p class="text-uppercase text-muted mb-0 link-text" style="font-size: 11px; letter-spacing: 1px; font-weight: bold;">Akun</p>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right"></i> <span class="link-text">Logout</span>
            </button>
        </form>
    </nav>

    <div class="sidebar-footer p-3 border-top border-secondary border-opacity-25">
        <button id="sidebarToggle" class="btn btn-outline-light w-100 btn-sm d-flex align-items-center justify-content-center gap-2" style="border-style: dashed; opacity: 0.7;">
            <i class="bi bi-chevron-left"></i> <span class="link-text">Tutup Menu</span>
        </button>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const body = document.body;
    const icon = toggleBtn.querySelector('i');
    const text = toggleBtn.querySelector('span');

    toggleBtn.addEventListener('click', function() {
        body.classList.toggle('sidebar-closed');

        if (body.classList.contains('sidebar-closed')) {
            text.style.display = 'none';
        } else {
            setTimeout(() => { text.style.display = 'inline'; }, 100);
        }
    });
</script>
