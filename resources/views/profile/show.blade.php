@extends('layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/profile/show.css') }}">
@endpush

@section('content')
<div class="profile-luxury-container">
    <div class="glass-profile-card" data-aos="fade-up">
        <div class="card-inner">
            
            {{-- SIDE KIRI --}}
            <div class="profile-side-brand">
                <div class="profile-avatar-main shadow-lg">
                    @if(Auth::user()->image)
                        <img src="{{ asset('uploads/users/'.Auth::user()->image) }}" alt="Avatar">
                    @else
                        <div class="initial-circle-main shadow">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h2 class="fw-800 mb-1">{{ Auth::user()->name }}</h2>

                <span class="badge bg-white text-danger rounded-pill px-3 py-1 fw-bold shadow-sm">
                    <i class="bi bi-patch-check-fill me-1"></i> MEMBER AKTIF
                </span>
                
                <div class="mt-5 text-white-50 small">
                    <p class="mb-0 opacity-75">Pelanggan Terverifikasi HK Equipment</p>

                    <p class="fw-bold text-white small mb-0">
                        Bergabung pada {{ Auth::user()->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            {{-- SIDE KANAN --}}
            <div class="profile-side-details">
                <div class="d-flex align-items-center justify-content-between mb-5">
                    <h4 class="text-white fw-bold mb-0">Informasi Akun</h4>
                    <span class="badge uid-badge">
                        UID: #{{ Auth::user()->id }}
                    </span>
                </div>

                <div class="data-row-modern">
                    <div class="detail-item shadow-sm">
                        <label><i class="bi bi-person-fill"></i> Nama Lengkap</label>
                        <p>{{ Auth::user()->name }}</p>
                    </div>
                    
                    <div class="detail-item shadow-sm">
                        <label><i class="bi bi-envelope-at-fill"></i> Email</label>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="detail-item shadow-sm">
                        <label><i class="bi bi-telephone-fill"></i> Nomor WhatsApp / Telepon</label>
                        <p>{{ Auth::user()->phone ?? 'Belum ditautkan' }}</p>
                    </div>
                </div>

                <div class="address-box-modern shadow-sm">
                    <label><i class="bi bi-geo-alt-fill"></i> Alamat / Lokasi</label>
                    <p class="text-white-50 mt-2 mb-0 fs-6" style="line-height: 1.6;">
                        {{ Auth::user()->address ?? 'Silakan tambahkan alamat untuk memudahkan pengiriman alat.' }}
                    </p>
                </div>

                <div class="action-group-modern">
                    <a href="{{ route('profile.edit') }}" class="btn-modern btn-primary-modern shadow">
                        <i class="bi bi-pencil-square me-2"></i> UBAH PROFIL
                    </a>
                    <a href="{{ route('customer.rentals') }}" class="btn-modern btn-secondary-modern">
                        <i class="bi bi-clock-history me-2"></i> RIWAYAT SEWA
                    </a>
                </div>

                <button class="btn-delete-link" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-shield-slash me-1"></i> Hapus akun secara permanen
                </button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-dark text-white border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-body p-4 text-center">
                <div class="text-danger fs-1 mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <h5 class="fw-bold">Hapus Akun?</h5>

                <p class="small text-white-50">
                    Tindakan ini bersifat permanen. Semua riwayat sewa Anda akan dihapus, Cah Bagus.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf @method('delete')

                    <input 
                        type="password" 
                        name="password" 
                        class="form-control mb-3 bg-secondary border-0 text-white shadow-none text-center" 
                        placeholder="Masukkan password untuk konfirmasi" 
                        required
                    >

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger flex-grow-1 rounded-pill fw-bold">
                            HAPUS
                        </button>

                        <button type="button" class="btn btn-light flex-grow-1 rounded-pill fw-bold" data-bs-dismiss="modal">
                            BATAL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/customer/profile/show.js') }}"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            background: '#1e1e26',
            color: '#fff',
            confirmButtonColor: '#ff416c'
        });
    </script>
    @endif

    @if ($errors->userDeletion->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: `{!! implode('<br>', $errors->userDeletion->all()) !!}`,
            background: '#1e1e26',
            color: '#fff',
            confirmButtonColor: '#ff416c'
        });
    </script>
    @endif
@endpush