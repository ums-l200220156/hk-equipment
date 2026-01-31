@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h2 class="fw-bolder text-dark mb-1">
                <i class="fas fa-tools text-danger me-2"></i> Manajemen Alat Berat
            </h2>
            <small class="text-muted">Kelola, pantau, dan filter armada secara cepat</small>
        </div>

        <a href="{{ route('admin.equipment.create') }}"
           class="btn btn-primary-custom fw-bold shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Alat
        </a>
    </header>

    {{-- QUICK STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted">Total Alat</small>
                    <h4 class="fw-bold mb-0">{{ $equipment->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted">Available</small>
                    <h4 class="fw-bold text-success mb-0">
                        {{ $equipment->where('status','available')->count() }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted">Rented</small>
                    <h4 class="fw-bold text-warning mb-0">
                        {{ $equipment->where('status','rented')->count() }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted">Maintenance</small>
                    <h4 class="fw-bold text-danger mb-0">
                        {{ $equipment->where('status','maintenance')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>


        {{-- FILTER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cari Nama Alat</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Excavator, Bulldozer...">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"
                                {{ request('category')==$cat?'selected':'' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
                        <option value="rented" {{ request('status')=='rented'?'selected':'' }}>Rented</option>
                        <option value="maintenance" {{ request('status')=='maintenance'?'selected':'' }}>Maintenance</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.equipment.index') }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>


        {{-- TABLE --}}
    <div class="card custom-card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Alat</th>
                            <th>Kategori</th>
                            <th>Harga / Jam</th>
                            <th>Status</th>
                            <th>Estimasi Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($equipment as $item)
                        <tr class="{{ $item->status === 'maintenance' ? 'table-danger' : '' }}">

                            {{-- FOTO --}}
                            <td>
                                <div style="width:70px;height:55px;overflow:hidden;border-radius:8px;">
                                    @if($item->image)
                                        <img src="{{ asset('uploads/equipment/'.$item->image) }}"
                                             class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="bg-light h-100 d-flex align-items-center justify-content-center text-muted small">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- INFO --}}
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">ID #{{ $item->id }}</small>
                            </td>

                            <td>
                                <span class="badge bg-secondary">{{ $item->category }}</span>
                            </td>

                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($item->price_per_hour,0,',','.') }}
                                </span>
                            </td>

                            <td>
                                <form method="POST"
                                    action="{{ route('admin.equipment.updateStatus', $item->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status"
                                            class="form-select form-select-sm fw-semibold mb-1
                                                @if($item->status=='available') text-success
                                                @elseif($item->status=='rented') text-warning
                                                @else text-danger @endif"
                                            onchange="this.form.submit()">

                                        <option value="available" {{ $item->status=='available'?'selected':'' }}>
                                            Available
                                        </option>
                                        <option value="rented" {{ $item->status=='rented'?'selected':'' }}>
                                            Rented
                                        </option>
                                        <option value="maintenance" {{ $item->status=='maintenance'?'selected':'' }}>
                                            Maintenance
                                        </option>
                                    </select>

                                    @if($item->status === 'maintenance')
                                        <input type="date"
                                            name="maintenance_end_at"
                                            value="{{ optional($item->maintenance_end_at)->format('Y-m-d') }}"
                                            class="form-control form-control-sm mt-1"
                                            onchange="this.form.submit()">
                                    @endif
                                </form>
                            </td>


                            <td>
                                @if($item->status === 'maintenance' && $item->maintenance_end_at)
                                    <span class="badge bg-warning text-dark">
                                        {{ $item->maintenance_end_at->translatedFormat('d M Y') }}
                                    </span>
                                @elseif($item->status === 'maintenance')
                                    <span class="text-muted small">Belum ditentukan</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>


                            {{-- AKSI --}}
                            <td class="text-center">
                                <a href="{{ route('admin.equipment.edit',$item->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.equipment.delete',$item->id) }}"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus {{ $item->name }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <p>Tidak ada alat ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>
@endsection
