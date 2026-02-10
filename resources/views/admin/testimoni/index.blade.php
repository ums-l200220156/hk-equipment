@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/testimoni/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-3">
    
    <div class="admin-welcome-header mb-4 shadow-sm">
        <div class="header-overlay"></div>
        <div class="header-content d-flex justify-content-between align-items-center">
            <div class="text-white">
                <h2 class="fw-800 mb-1">MANAJEMEN <span class="text-danger">TESTIMONI</span></h2>
                <p class="opacity-75 mb-0"><i class="bi bi-chat-quote me-1"></i> Kelola ulasan pelanggan HK Equipment</p>
            </div>
            <div class="header-actions">
                <div class="total-testimoni-badge">
                    <span class="small opacity-75">Total Ulasan:</span>
                    <span class="fw-bold">{{ $testimonis->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr class="small text-uppercase tracking-wider">
                        <th class="ps-4 py-3">Informasi Klien</th>
                        <th>Penilaian</th>
                        <th>Isi Ulasan</th>
                        <th>Lampiran Media</th>
                        <th class="text-end pe-4">Kendali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonis as $t)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($t->user->name) }}&background=dc3545&color=fff" class="rounded-circle me-3 border shadow-sm" width="45">
                                <div>
                                    <div class="fw-bold text-dark">{{ $t->user->name }}</div>
                                    <small class="text-muted">{{ $t->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="star-rating text-warning">
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi {{ $i <= $t->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div class="testimoni-text">
                                <i class="bi bi-quote text-danger opacity-25 fs-4"></i>
                                <span>{{ $t->content }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="media-preview-container">
                                @if($t->photo)
                                    <button type="button" class="btn-preview img-trigger" onclick="viewMedia('{{ asset('storage/'.$t->photo) }}', 'image')">
                                        <img src="{{ asset('storage/'.$t->photo) }}" alt="Preview">
                                        <div class="overlay"><i class="bi bi-zoom-in"></i></div>
                                    </button>
                                @endif
                                
                                @if($t->video)
                                    <button type="button" class="btn-preview vid-trigger" onclick="viewMedia('{{ asset('storage/'.$t->video) }}', 'video')">
                                        <div class="video-placeholder">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                        <div class="overlay">Lihat Video</div>
                                    </button>
                                @endif

                                @if(!$t->photo && !$t->video)
                                    <span class="text-muted small italic">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button onclick="confirmDelete({{ $t->id }})" class="btn-delete-testi">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            <form id="delete-form-{{ $t->id }}" action="{{ route('admin.testimonis.destroy', $t->id) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-chat-dots fs-1 text-muted opacity-25"></i>
                            <p class="mt-2 text-muted fw-bold">Belum ada testimoni masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                <div id="mediaContainer" class="rounded-4 overflow-hidden shadow-lg">
                    </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/admin/testimoni/index.js') }}"></script>
@endpush
@endsection