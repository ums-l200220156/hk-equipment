@extends('layouts.admin')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container py-4">

    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="fw-bolder text-dark">
            <i class="bi bi-people-fill text-warning me-2"></i> Daftar Pelanggan
        </h2>

        <a href="{{ route('admin.customer.create') }}" 
           class="btn btn-warning fw-bold btn-sm shadow-sm text-dark">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pelanggan
        </a>
    </header>

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mb-0">
                    
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th class="text-nowrap">NO</th>
                            <th>Nama</th>
                            <th class="text-nowrap">Nomor Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Catatan</th>
                            <th class="text-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <!-- NO -->
                            <td class="text-center fw-bold text-nowrap">{{ $loop->iteration }}</td>

                            <!-- NAMA -->
                            <td class="fw-semibold text-dark">{{ $c->name }}</td>

                            <!-- NOMOR TELEPON -->
                            <td class="text-center text-nowrap">
                                <span class="badge bg-secondary px-3 py-2">{{ $c->phone }}</span>
                            </td>

                            <!-- EMAIL -->
                            <td class="small">{{ $c->email }}</td>

                            <!-- ALAMAT -->
                            <td class="small text-muted">
                                {{ Str::limit($c->address, 40) }}
                            </td>

                            <!-- CATATAN -->
                            <td class="small text-info">
                                {{ $c->notes ?? '-' }}
                            </td>

                            <!-- AKSI -->
                            <td class="text-center text-nowrap">

                                <a href="{{ route('admin.customer.edit', $c->id) }}" 
                                   class="btn btn-sm btn-outline-warning px-3">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.customer.delete', $c->id) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus pelanggan {{ $c->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger px-3">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>
@endsection
