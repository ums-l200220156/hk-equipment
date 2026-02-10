<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HK Equipment' }} | Admin Panel</title>

    {{-- 1. FIX FLICKER: Script ini diletakkan di HEAD agar berjalan SEBELUM elemen digambar --}}
    <script>
        (function() {
            const savedState = localStorage.getItem('hk_sidebar_state');
            if (savedState === 'closed') {
                document.documentElement.classList.add('sidebar-closed');
            }
        })();
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-style.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

    @include('admin.partials.sidebar')

    <main class="main-content">
        <nav class="navbar navbar-mobile d-md-none mb-4">
            <div class="container-fluid">
                <span class="navbar-brand fw-bold text-danger">HK ADMIN</span>
                <button class="btn btn-outline-dark" id="mobileToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>

        <div class="container-fluid content-inner">
            @yield('content')
        </div>

        @include('admin.partials.footer')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/admin-script.js') }}"></script>

    {{-- SweetAlert Global Handler --}}
    @if(session('swal_success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('swal_success') }}", timer: 2000, showConfirmButton: false, borderRadius: '15px' });
    </script>
    @endif

    @if(session('swal_error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('swal_error') }}", confirmButtonColor: '#dc3545' });
    </script>
    @endif

    @stack('scripts')
</body>
</html>