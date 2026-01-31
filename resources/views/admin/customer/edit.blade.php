@extends('layouts.admin')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">
        <i class="bi bi-pencil-square text-warning me-2"></i>
        Edit Data Pelanggan
    </h2>

    <div class="card shadow border-0">
        <div class="card-body">

            <form action="{{ route('admin.customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $customer->name) }}" required>
                </div>

                {{-- TELEPON --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $customer->phone) }}" required>
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $customer->email) }}" required>
                </div>

                {{-- ALAMAT --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="address" rows="3"
                              class="form-control" required>{{ old('address', $customer->address) }}</textarea>
                </div>

                {{-- CATATAN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3"
                              class="form-control">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-success fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
