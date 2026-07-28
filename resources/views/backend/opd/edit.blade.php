@extends('backend.layouts.main')

@section('title', ' | Edit OPD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Edit OPD</h5>
    <a href="{{ route('opd.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('opd.update', $item->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="kode_opd" class="form-label">Kode OPD</label>
                <input type="text" name="kode_opd" id="kode_opd" class="form-control @error('kode_opd') is-invalid @enderror" value="{{ old('kode_opd', $item->kode_opd) }}" placeholder="Masukkan kode OPD">
                @error('kode_opd') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama_opd" class="form-label">Nama OPD</label>
                <input type="text" name="nama_opd" id="nama_opd" class="form-control @error('nama_opd') is-invalid @enderror" value="{{ old('nama_opd', $item->nama_opd) }}" placeholder="Masukkan nama OPD">
                @error('nama_opd') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat (Opsional)</label>
                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat">{{ old('alamat', $item->alamat) }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="telp" class="form-label">Telp (Opsional)</label>
                <input type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror" value="{{ old('telp', $item->telp) }}" placeholder="Masukkan nomor telepon">
                @error('telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email (Opsional)</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $item->email) }}" placeholder="Masukkan email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="website" class="form-label">Website (Opsional)</label>
                <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $item->website) }}" placeholder="Masukkan URL website">
                @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', $item->is_active) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('opd.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
