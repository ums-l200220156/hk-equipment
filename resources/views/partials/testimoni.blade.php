<section id="testimoni" class="section-padding">
    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-5">
            <span class="text-danger fw-bold text-uppercase">Testimoni Klien</span>
            <h2 class="display-5 fw-bold text-dark">Dipercaya oleh Pelanggan</h2>
            <p class="text-muted mx-auto" style="max-width:600px">
                Pengalaman nyata pelanggan HK Equipment.
            </p>
        </div>

        @php
            $myTestimoni = auth()->check()
                ? ($testimonis ?? collect())->firstWhere('user_id', auth()->id())
                : null;
        @endphp

        {{-- ================= CAROUSEL ================= --}}
        <div id="carouselTestimoni"
            class="carousel slide mb-4"
            data-bs-ride="carousel"
            data-bs-interval="10000"
            data-bs-touch="true">


            <div class="carousel-inner">

                @forelse($testimonis ?? [] as $i => $testimoni)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <div class="testimoni-premium-card position-relative">

                            {{-- ACTION OWNER --}}
                            @auth
                                @if(auth()->id() === $testimoni->user_id)
                                    <div class="dropdown position-absolute top-0 end-0 m-2" data-bs-auto-close="outside">
                                        <button type="button"
                                                class="btn btn-sm btn-light rounded-circle shadow"
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li>
                                                <button type="button"
                                                        class="dropdown-item btn-edit-testimoni"
                                                        data-id="{{ $testimoni->id }}"
                                                        data-rating="{{ $testimoni->rating }}"
                                                        data-content="{{ $testimoni->content }}">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button"
                                                        class="dropdown-item text-danger btn-delete-testimoni"
                                                        data-id="{{ $testimoni->id }}">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                @endif
                            @endauth

                            {{-- ================= USER INFO ================= --}}
                            <div class="d-flex align-items-center justify-content-center mb-3">
                              <div class="testimoni-avatar mb-3">
                                    @if($testimoni->user->image)
                                        <img src="{{ asset('uploads/users/' . $testimoni->user->image) }}" 
                                            alt="{{ $testimoni->user->name }}"
                                            style="width: 90px; height: 90px; object-fit: cover; border-radius: 20px;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($testimoni->user->name) }}&background=dc3545&color=fff&size=128"
                                            alt="{{ $testimoni->user->name }}"
                                            style="width: 90px; height: 90px; border-radius: 20px;">
                                    @endif
                                </div>
                                <div class="testimoni-meta ms-3"> 
                                    <h5 class="fw-bold mb-1" style="color: #1e293b;">
                                        {{ $testimoni->user->name }}
                                    </h5>
                                    <div class="testimoni-rating text-warning">
                                        @for($j=1;$j<=5;$j++)
                                            {{ $j <= $testimoni->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            {{-- ================= FOTO TESTIMONI ================= --}}
                            @if($testimoni->photo)
                                <div class="mb-3 text-center">
                                    <img src="{{ asset('storage/'.$testimoni->photo) }}"
                                         class="img-fluid rounded-4 shadow-sm"
                                         style="max-height:320px;object-fit:cover;">
                                </div>
                            @endif

                            {{-- ================= VIDEO TESTIMONI ================= --}}
                            @if($testimoni->video)
                                <div class="ratio ratio-16x9 mb-3 rounded-4 overflow-hidden shadow-sm">
                                    <video controls preload="metadata">
                                        <source src="{{ asset('storage/'.$testimoni->video) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endif

                            {{-- ================= ISI ================= --}}
                            <p class="testimoni-content text-center">
                                “{{ $testimoni->content }}”
                            </p>

                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="testimoni-premium-card text-center">
                            <p class="text-muted mb-0">
                                Belum ada testimoni dari pelanggan.
                            </p>
                        </div>
                    </div>
                @endforelse

            </div>

            {{-- DOTS --}}
            @if(($testimonis ?? collect())->count() > 1)
                <div class="carousel-indicators position-static mt-4">
                    @foreach($testimonis as $i => $t)
                        <button type="button"
                                data-bs-target="#carouselTestimoni"
                                data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
            @endif

            {{-- ARROW --}}
            @if(($testimonis ?? collect())->count() > 1)
                <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselTestimoni"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselTestimoni"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>

        {{-- HINT --}}
        @if(($testimonis ?? collect())->count() > 1)
            <div class="text-center text-muted mb-5" style="font-size:.9rem">
                <i class="bi bi-arrow-left-right me-1"></i>
                Geser ke kiri / kanan untuk melihat testimoni lainnya
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <div class="row justify-content-center">
            <div class="col-lg-7">

                @auth
                    @if(!$myTestimoni)
                        <div class="card shadow-lg border-0 rounded-4 p-4">
                            <h4 class="fw-bold text-center mb-3">
                                💬 Tambahkan Testimoni Anda
                            </h4>

                            <form id="form-testimoni"
                                  method="POST"
                                  action="{{ route('testimoni.store') }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="fw-bold">Rating *</label>
                                    <select name="rating" class="form-select" required>
                                        <option value="">Pilih</option>
                                        @for($i=5;$i>=1;$i--)
                                            <option value="{{ $i }}">{{ str_repeat('★',$i) }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Pengalaman *</label>
                                    <textarea name="content"
                                              class="form-control"
                                              rows="4"
                                              required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Foto / Video</label>
                                    <input type="file"
                                           name="media"
                                           class="form-control"
                                           accept="image/*,video/*">
                                </div>

                                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                                    Kirim Testimoni
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info text-center shadow-sm">
                            <i class="bi bi-info-circle me-2"></i>
                            Anda sudah mengirim <strong>1 testimoni</strong>.
                            Silakan edit atau hapus testimoni Anda.
                        </div>
                    @endif
                @endauth

                @guest
                    <div class="text-center">
                        <a href="{{ route('login') }}"
                           class="btn btn-outline-danger fw-bold">
                            Login untuk Menambahkan Testimoni
                        </a>
                    </div>
                @endguest

            </div>
        </div>

    </div>
</section>




<div class="modal fade" id="modalEditTestimoni" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Testimoni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form-edit-testimoni" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="edit-testimoni-id">

                    <div class="mb-3">
                        <label class="fw-bold">Rating</label>
                        <select id="edit-rating"
                                name="rating"
                                class="form-select"
                                required>
                            @for($i=5;$i>=1;$i--)
                                <option value="{{ $i }}">{{ str_repeat('★',$i) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Isi Testimoni</label>
                        <textarea id="edit-content"
                                  name="content"
                                  class="form-control"
                                  rows="4"
                                  required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Ganti Foto / Video</label>
                        <input type="file"
                               name="media"
                               class="form-control"
                               accept="image/*,video/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn btn-danger fw-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
