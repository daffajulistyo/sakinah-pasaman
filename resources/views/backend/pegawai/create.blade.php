@extends('backend.layouts.main')

@section('title', ' | Tambah Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Tambah Pegawai</h5>
    <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gelar_depan" class="form-label">Gelar Depan (Opsional)</label>
                    <input type="text" name="gelar_depan" id="gelar_depan" class="form-control @error('gelar_depan') is-invalid @enderror" value="{{ old('gelar_depan') }}" placeholder="Masukkan gelar depan">
                    @error('gelar_depan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gelar_belakang" class="form-label">Gelar Belakang (Opsional)</label>
                    <input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control @error('gelar_belakang') is-invalid @enderror" value="{{ old('gelar_belakang') }}" placeholder="Masukkan gelar belakang">
                    @error('gelar_belakang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir (Opsional)</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Masukkan tempat lahir">
                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir (Opsional)</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label">No HP (Opsional)</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="Masukkan nomor HP">
                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email (Opsional)</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="opd_id" class="form-label">OPD</label>
                    <select name="opd_id" id="opd_id" class="form-select @error('opd_id') is-invalid @enderror">
                        <option value="">-- Pilih OPD --</option>
                        @foreach($opds as $item)
                            <option value="{{ $item->id }}" {{ old('opd_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_opd }}</option>
                        @endforeach
                    </select>
                    @error('opd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="eselon_id" class="form-label">Eselon</label>
                    <select name="eselon_id" id="eselon_id" class="form-select @error('eselon_id') is-invalid @enderror">
                        <option value="">-- Pilih Eselon --</option>
                        @foreach($eselons as $item)
                            <option value="{{ $item->id }}" {{ old('eselon_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('eselon_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="golongan_id" class="form-label">Golongan</label>
                    <select name="golongan_id" id="golongan_id" class="form-select @error('golongan_id') is-invalid @enderror">
                        <option value="">-- Pilih Golongan --</option>
                        @foreach($golongans as $item)
                            <option value="{{ $item->id }}" {{ old('golongan_id') == $item->id ? 'selected' : '' }}>{{ $item->golongan }} - {{ $item->pangkat }}</option>
                        @endforeach
                    </select>
                    @error('golongan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jenis_jabatan_id" class="form-label">Jenis Jabatan</label>
                    <select name="jenis_jabatan_id" id="jenis_jabatan_id" class="form-select @error('jenis_jabatan_id') is-invalid @enderror">
                        <option value="">-- Pilih Jenis Jabatan --</option>
                        @foreach($jenisJabatans as $item)
                            <option value="{{ $item->id }}" {{ old('jenis_jabatan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('jenis_jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jabatan_id" class="form-label">Jabatan</label>
                    <select name="jabatan_id" id="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $item)
                            <option value="{{ $item->id }}" {{ old('jabatan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jenjang" class="form-label">Jenjang (Opsional)</label>
                    <input type="text" name="jenjang" id="jenjang" class="form-control @error('jenjang') is-invalid @enderror" value="{{ old('jenjang') }}" placeholder="Masukkan jenjang">
                    @error('jenjang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat (Opsional)</label>
                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1" {{ old('aktif', 1) ? 'checked' : '' }}>
                <label for="aktif" class="form-check-label">Aktif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
