@extends('backend.layouts.main')
@section('title', 'Master Kegiatan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Master Kegiatan</h5>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2">
                <div class="col-auto flex-grow-1"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ $search }}"></div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button></div>
                @if($search)<div class="col-auto"><a href="{{ route('kegiatan.index') }}" class="btn btn-sm btn-outline-danger">&times; Reset</a></div>@endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Kegiatan</th>
                        <th>Program</th>
                        <th>Anggaran</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $row->kode_kegiatan }}</td>
                        <td>{{ $row->nama_kegiatan }}</td>
                        <td>{{ $row->program->kode_program ?? '-' }} - {{ $row->program->nama_program ?? '-' }}</td>
                        <td>Rp {{ number_format($row->anggaran, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $row->is_active == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $row->is_active == 1 ? 'Aktif' : 'Non' }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-sm-table" title="Edit"
                                data-bs-toggle="modal" data-bs-target="#formModal"
                                data-mode="edit"
                                data-id="{{ $row->id }}"
                                data-kode="{{ $row->kode_kegiatan }}"
                                data-nama="{{ $row->nama_kegiatan }}"
                                data-program="{{ $row->master_program_id }}"
                                data-anggaran="{{ $row->anggaran }}"
                                data-aktif="{{ $row->is_active }}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-sm-table" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('kegiatan.destroy', $row->id) }}"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
        <div class="px-3 py-2 border-top">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- Form Modal --}}
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formModalForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="formModalTitle">Tambah Kegiatan</h6>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="kode_kegiatan" class="form-label">Kode Kegiatan</label>
                        <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label for="master_program_id" class="form-label">Program</label>
                        <select name="master_program_id" id="master_program_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach($programs as $p)
                            <option value="{{ $p->id }}">{{ $p->kode_program }} - {{ $p->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="anggaran" class="form-label">Anggaran (Rp)</label>
                        <input type="number" name="anggaran" id="anggaran" class="form-control form-control-sm" value="0">
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
            title.textContent = 'Edit Kegiatan';
            frm.action = '{{ url("backend/kegiatan") }}/' + btn.getAttribute('data-id');
            method.value = 'PUT';
            document.getElementById('kode_kegiatan').value = btn.getAttribute('data-kode');
            document.getElementById('nama_kegiatan').value = btn.getAttribute('data-nama');
            document.getElementById('master_program_id').value = btn.getAttribute('data-program');
            document.getElementById('anggaran').value = btn.getAttribute('data-anggaran') || 0;
            document.getElementById('is_active').checked = btn.getAttribute('data-aktif') == '1';
        } else {
            title.textContent = 'Tambah Kegiatan';
            frm.action = '{{ route("kegiatan.store") }}';
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
