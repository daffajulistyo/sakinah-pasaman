@extends('backend.layouts.main')

@section('title', ' | Edit Jabatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Edit Jabatan</h5>
    <a href="{{ route('jabatan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('jabatan.update', $item->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $item->kode) }}" placeholder="Masukkan kode">
                @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Jabatan</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $item->nama) }}" placeholder="Masukkan nama jabatan">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="jenis_jabatan_id" class="form-label">Jenis Jabatan</label>
                <select name="jenis_jabatan_id" id="jenis_jabatan_id" class="form-select @error('jenis_jabatan_id') is-invalid @enderror">
                    <option value="">-- Pilih Jenis Jabatan --</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->id }}" {{ old('jenis_jabatan_id', $item->ref_jenis_jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', $item->is_active) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('jabatan.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
