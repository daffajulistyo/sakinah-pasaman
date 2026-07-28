@extends('backend.layouts.main')

@section('title', ' | Tambah Jenis Jabatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Tambah Jenis Jabatan</h5>
    <a href="{{ route('jenis-jabatan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('jenis-jabatan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Masukkan kode">
                @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Jenis Jabatan</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama jenis jabatan">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', 1) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('jenis-jabatan.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
