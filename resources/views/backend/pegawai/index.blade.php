@extends('backend.layouts.main')
@section('title', 'Master Pegawai')
@section('page-title', 'Master Pegawai')
@section('main-content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Master Pegawai</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <form method="GET" class="row g-2">
                <div class="col-auto flex-grow-1"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ $search }}"></div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button></div>
                @if($search)<div class="col-auto"><a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-danger">&times; Reset</a></div>@endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>OPD</th>
                        <th>Golongan</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $row->nip }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->opd->nama_opd ?? '-' }}</td>
                        <td>{{ $row->refGolongan->golongan ?? '-' }}</td>
                        <td>{{ $row->refJabatan->nama ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $row->is_active == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $row->is_active == 1 ? 'Aktif' : 'Non' }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-sm-table" title="Edit"
                                data-bs-toggle="modal" data-bs-target="#formModal"
                                data-mode="edit"
                                data-id="{{ $row->id }}"
                                data-nip="{{ $row->nip }}"
                                data-nama="{{ $row->nama }}"
                                data-gelar_depan="{{ $row->gelar_depan }}"
                                data-gelar_belakang="{{ $row->gelar_belakang }}"
                                data-tempat_lahir="{{ $row->tempat_lahir }}"
                                data-tanggal_lahir="{{ $row->tanggal_lahir }}"
                                data-jenis_kelamin="{{ $row->jenis_kelamin }}"
                                data-alamat="{{ $row->alamat }}"
                                data-no_hp="{{ $row->no_hp }}"
                                data-email="{{ $row->email }}"
                                data-opd="{{ $row->master_opd_id }}"
                                data-eselon="{{ $row->ref_eselon_id }}"
                                data-golongan="{{ $row->ref_golongan_id }}"
                                data-jenis_jabatan="{{ $row->ref_jenis_jabatan_id }}"
                                data-jabatan="{{ $row->ref_jabatan_id }}"
                                data-jenjang="{{ $row->jenjang }}"
                                data-aktif="{{ $row->is_active }}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-sm-table" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('pegawai.destroy', $row->id) }}"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($data->hasPages())
    <div class="card-footer clearfix">
        <div class="float-end">{{ $data->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>

{{-- Form Modal --}}
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formModalForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="formModalTitle">Tambah Pegawai</h6>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="row mb-3">
                        <div class="col"><label for="nip" class="form-label">NIP</label><input type="text" name="nip" id="nip" class="form-control form-control-sm" required></div>
                        <div class="col"><label for="nama" class="form-label">Nama</label><input type="text" name="nama" id="nama" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col"><label for="gelar_depan" class="form-label">Gelar Depan</label><input type="text" name="gelar_depan" id="gelar_depan" class="form-control form-control-sm"></div>
                        <div class="col"><label for="gelar_belakang" class="form-label">Gelar Belakang</label><input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control form-control-sm"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col"><label for="tempat_lahir" class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control form-control-sm"></div>
                        <div class="col"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control form-control-sm"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col"><label for="no_hp" class="form-label">No. HP</label><input type="text" name="no_hp" id="no_hp" class="form-control form-control-sm"></div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="master_opd_id" class="form-label">OPD</label>
                            <select name="master_opd_id" id="master_opd_id" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($opds as $o)
                                <option value="{{ $o->id }}">{{ $o->nama_opd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="ref_eselon_id" class="form-label">Eselon</label>
                            <select name="ref_eselon_id" id="ref_eselon_id" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($eselons as $e)
                                <option value="{{ $e->id }}">{{ $e->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="ref_golongan_id" class="form-label">Golongan</label>
                            <select name="ref_golongan_id" id="ref_golongan_id" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($golongans as $g)
                                <option value="{{ $g->id }}">{{ $g->golongan }} - {{ $g->pangkat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="ref_jenis_jabatan_id" class="form-label">Jenis Jabatan</label>
                            <select name="ref_jenis_jabatan_id" id="ref_jenis_jabatan_id" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($jenisJabatans as $jj)
                                <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="ref_jabatan_id" class="form-label">Jabatan</label>
                            <select name="ref_jabatan_id" id="ref_jabatan_id" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($jabatans as $jb)
                                <option value="{{ $jb->id }}">{{ $jb->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="jenjang" class="form-label">Jenjang</label>
                            <input type="text" name="jenjang" id="jenjang" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                        <label for="is_active" class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">Yakin hapus data ini? Data tidak dapat dikembalikan.</div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formModal = document.getElementById('formModal');
    const frm = document.getElementById('formModalForm');
    const method = document.getElementById('formMethod');
    const title = document.getElementById('formModalTitle');
    formModal.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        const mode = btn.getAttribute('data-mode') || 'add';
        if (mode === 'edit') {
            title.textContent = 'Edit Pegawai';
            frm.action = '{{ url("backend/pegawai") }}/' + btn.getAttribute('data-id');
            method.value = 'PUT';
            document.getElementById('nip').value = btn.getAttribute('data-nip');
            document.getElementById('nama').value = btn.getAttribute('data-nama');
            document.getElementById('gelar_depan').value = btn.getAttribute('data-gelar_depan') || '';
            document.getElementById('gelar_belakang').value = btn.getAttribute('data-gelar_belakang') || '';
            document.getElementById('tempat_lahir').value = btn.getAttribute('data-tempat_lahir') || '';
            document.getElementById('tanggal_lahir').value = btn.getAttribute('data-tanggal_lahir') || '';
            document.getElementById('jenis_kelamin').value = btn.getAttribute('data-jenis_kelamin') || '';
            document.getElementById('alamat').value = btn.getAttribute('data-alamat') || '';
            document.getElementById('no_hp').value = btn.getAttribute('data-no_hp') || '';
            document.getElementById('email').value = btn.getAttribute('data-email') || '';
            document.getElementById('master_opd_id').value = btn.getAttribute('data-opd') || '';
            document.getElementById('ref_eselon_id').value = btn.getAttribute('data-eselon') || '';
            document.getElementById('ref_golongan_id').value = btn.getAttribute('data-golongan') || '';
            document.getElementById('ref_jenis_jabatan_id').value = btn.getAttribute('data-jenis_jabatan') || '';
            document.getElementById('ref_jabatan_id').value = btn.getAttribute('data-jabatan') || '';
            document.getElementById('jenjang').value = btn.getAttribute('data-jenjang') || '';
            document.getElementById('is_active').checked = btn.getAttribute('data-aktif') == '1';
        } else {
            title.textContent = 'Tambah Pegawai';
            frm.action = '{{ route("pegawai.store") }}';
            method.value = 'POST';
            frm.reset();
            document.getElementById('is_active').checked = true;
        }
    });
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function(e) {
        document.getElementById('deleteForm').action = e.relatedTarget.getAttribute('data-action');
    });
});
</script>
@endpush
@endsection
