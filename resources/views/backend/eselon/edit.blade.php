@extends('backend.layouts.main')

@section('title', ' | Edit Eselon')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Edit Eselon</h5>
    <a href="{{ route('eselon.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('eselon.update', $item->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $item->kode) }}" placeholder="Masukkan kode">
                @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Eselon</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $item->nama) }}" placeholder="Masukkan nama eselon">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <input type="number" name="level" id="level" class="form-control @error('level') is-invalid @enderror" value="{{ old('level', $item->level) }}" placeholder="Masukkan level">
                @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', $item->is_active) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('eselon.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
