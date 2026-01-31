<section id="proses" class="section-padding bg-white">
<div class="container text-center">
    <h2 class="display-6 fw-bold mb-5">Proses Sewa Mudah</h2>

    <div class="row g-4">
        @foreach([
            ['1','Pilih Alat'],
            ['2','Pesan & Bayar'],
            ['3','Alat Siap Kerja']
        ] as $step)
        <div class="col-md-4">
            <div class="step-box">
                <div class="fs-1 fw-bold text-danger">{{ $step[0] }}</div>
                <h5 class="fw-bold mt-2">{{ $step[1] }}</h5>
                <p class="text-muted">Proses cepat dan transparan.</p>
            </div>
        </div>
        @endforeach
    </div>

    <a href="{{ route('customer.catalog') }}"
       class="btn btn-danger btn-lg mt-4 fw-bold shadow">
        Mulai Sewa Sekarang
    </a>
</div>
</section>
