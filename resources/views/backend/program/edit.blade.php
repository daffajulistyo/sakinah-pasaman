@extends('backend.layouts.main')

@section('title', ' | Edit Program')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Edit Program</h5>
    <a href="{{ route('program.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('program.update', $item->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="kode_program" class="form-label">Kode Program</label>
                <input type="text" name="kode_program" id="kode_program" class="form-control @error('kode_program') is-invalid @enderror" value="{{ old('kode_program', $item->kode_program) }}" placeholder="Masukkan kode program">
                @error('kode_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama_program" class="form-label">Nama Program</label>
                <input type="text" name="nama_program" id="nama_program" class="form-control @error('nama_program') is-invalid @enderror" value="{{ old('nama_program', $item->nama_program) }}" placeholder="Masukkan nama program">
                @error('nama_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="kode_opd" class="form-label">OPD</label>
                <select name="kode_opd" id="kode_opd" class="form-select @error('kode_opd') is-invalid @enderror">
                    <option value="">-- Pilih OPD --</option>
                    @foreach($opds as $opd)
                        <option value="{{ $opd->kode_opd }}" {{ old('kode_opd', $item->kode_skpd) == $opd->kode_opd ? 'selected' : '' }}>{{ $opd->nama_opd }}</option>
                    @endforeach
                </select>
                @error('kode_opd') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $item->tahun) }}" placeholder="Masukkan tahun">
                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', $item->is_active) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('program.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
