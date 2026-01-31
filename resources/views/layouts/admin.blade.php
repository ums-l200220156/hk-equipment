<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HK Equipment Admin Panel' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- GLOBAL ADMIN STYLE -->
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f5f6f8; 
            overflow-x: hidden; 
            transition: all 0.3s;
        }

        /* MAIN CONTENT */
        .main-content { 
            margin-left: 260px; 
            padding: 30px; 
            transition: all 0.3s ease-in-out; 
        }
        body.sidebar-closed .main-content { margin-left: 80px; }

        /* STYLING BAGIAN DASHBOARD/INDEX */
        /* CARD STAT */
        .card-stat { 
            border: none; 
            border-radius: 15px; 
            color: white; 
            position: relative; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            height: 100%; 
            transition: transform 0.3s; 
        }
        .card-stat:hover { transform: translateY(-5px); }

        .card-stat .card-body { padding: 25px; position: relative; z-index: 2; }
        .stat-icon-bg { 
            position: absolute; 
            right: -10px; 
            bottom: -10px; 
            font-size: 5rem; 
            opacity: 0.2; 
            z-index: 1; 
            transform: rotate(-15deg); 
        }

        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
        .bg-gradient-danger  { background: linear-gradient(45deg, #e74a3b, #be2617); }
        .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); }
        .bg-gradient-info    { background: linear-gradient(45deg, #36b9cc, #258391); }

        /* TABLE CARD */
        .table-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.05); 
            border: none; 
            overflow: hidden; 
        }
        .table thead th { 
            background-color: #f8f9fc; 
            color: #5a5c69; 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            border-bottom: 2px solid #e3e6f0; 
            padding: 15px; 
        }
        .table tbody td { 
            padding: 15px; 
            vertical-align: middle; 
            color: #5a5c69; 
            border-bottom: 1px solid #e3e6f0; 
        }

        .badge-status { 
            padding: 8px 12px; 
            border-radius: 30px; 
            font-size: 0.75rem; 
            font-weight: 600; 
            text-transform: uppercase; 
        }

        /* PRINT BUTTON */
        .btn-print { 
            background-color: white; 
            border: 2px solid #212529; 
            color: #212529; 
            font-weight: 600; 
            padding: 8px 20px; 
            border-radius: 8px; 
            transition: all 0.3s; 
        }
        .btn-print:hover { background-color: #212529; color: white; }


        /* STYLING BAGIAN EQUIPMENT/INDEX */

        /* CUSTOM BUTTON */
        .btn-primary-custom {
            background-color: var(--primary-admin);
            border-color: var(--primary-admin);
            color: red;
        }
        .btn-primary-custom:hover {
            background-color: #00c25eff;
            border-color: #00c25eff;
            color: white;
        }

        /* CARD */
        .custom-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        /* TABLE */
        .table-custom th {
            background-color: var(--dark-bg-card);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        .table-custom tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* STATUS BADGE */
        .status-badge {
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            font-size: 0.8em;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-ready { background-color: #d4edda; color: #155724; }
        .status-rented { background-color: #ffc107; color: #856404; }
        .status-maintenance { background-color: #f8d7da; color: #721c24; }


        /* STYLE TABEL ADMIN/CUSTOMER/INDEX */
        table {
            table-layout: auto;
            width: 100%;
        }
</style>



    </style>

    @stack('styles')

</head>

<body>

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN PAGE --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')


    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('swal_success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Status transaksi berhasil diperbarui',
        timer: 1800,
        showConfirmButton: false
    });
</script>
@endif

@if(session('swal_error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Terjadi kesalahan pada sistem',
    });
</script>
@endif



</body>
</html>
