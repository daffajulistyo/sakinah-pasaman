@extends('backend.layouts.main')

@section('title', ' | Tambah Anggaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Tambah Anggaran</h5>
    <a href="{{ route('anggaran.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('anggaran.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kode_anggaran" class="form-label">Kode Anggaran</label>
                <input type="text" name="kode_anggaran" id="kode_anggaran" class="form-control @error('kode_anggaran') is-invalid @enderror" value="{{ old('kode_anggaran') }}" placeholder="Masukkan kode anggaran">
                @error('kode_anggaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nama_anggaran" class="form-label">Nama Anggaran</label>
                <input type="text" name="nama_anggaran" id="nama_anggaran" class="form-control @error('nama_anggaran') is-invalid @enderror" value="{{ old('nama_anggaran') }}" placeholder="Masukkan nama anggaran">
                @error('nama_anggaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="opd_id" class="form-label">OPD</label>
                <select name="opd_id" id="opd_id" class="form-select @error('opd_id') is-invalid @enderror">
                    <option value="">-- Pilih OPD --</option>
                    @foreach($opds as $item)
                        <option value="{{ $item->id }}" {{ old('opd_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_opd }}</option>
                    @endforeach
                </select>
                @error('opd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="program_id" class="form-label">Program</label>
                <select name="program_id" id="program_id" class="form-select @error('program_id') is-invalid @enderror">
                    <option value="">-- Pilih Program --</option>
                    @foreach($programs as $item)
                        <option value="{{ $item->id }}" {{ old('program_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_program }}</option>
                    @endforeach
                </select>
                @error('program_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" placeholder="Masukkan jumlah">
                @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun') }}" placeholder="Masukkan tahun">
                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', 1) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('anggaran.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
