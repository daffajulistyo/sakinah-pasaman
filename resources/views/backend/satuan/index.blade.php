@extends('backend.layouts.main')
@section('title', 'Master Satuan')
@section('page-title', 'Master Satuan')
@section('main-content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Master Satuan</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <form method="GET" class="row g-2">
                <div class="col-auto flex-grow-1"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ $search }}"></div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button></div>
                @if($search)<div class="col-auto"><a href="{{ route('satuan.index') }}" class="btn btn-sm btn-outline-danger">&times; Reset</a></div>@endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $row->satuan }}</td>
                        <td>{{ $row->keterangan ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $row->is_active == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $row->is_active == 1 ? 'Aktif' : 'Non' }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-sm-table" title="Edit"
                                data-bs-toggle="modal" data-bs-target="#formModal"
                                data-mode="edit"
                                data-id="{{ $row->id }}"
                                data-satuan="{{ $row->satuan }}"
                                data-keterangan="{{ $row->keterangan }}"
                                data-aktif="{{ $row->is_active }}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-sm-table" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('satuan.destroy', $row->id) }}"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data.</td></tr>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formModalForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="formModalTitle">Tambah Satuan</h6>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1">
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
            title.textContent = 'Edit Satuan';
            frm.action = '{{ url("backend/satuan") }}/' + btn.getAttribute('data-id');
            method.value = 'PUT';
            document.getElementById('satuan').value = btn.getAttribute('data-satuan');
            document.getElementById('keterangan').value = btn.getAttribute('data-keterangan') || '';
            document.getElementById('is_active').checked = btn.getAttribute('data-aktif') == '1';
        } else {
            title.textContent = 'Tambah Satuan';
            frm.action = '{{ route("satuan.store") }}';
            method.value = 'POST';
            frm.reset();
        }
    });
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function(e) {
        document.getElementById('deleteForm').action = e.relatedTarget.getAttribute('data-action');
    });
});
</script>
@endpush
@endsection
