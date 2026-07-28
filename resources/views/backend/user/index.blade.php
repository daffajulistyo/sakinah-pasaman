@extends('backend.layouts.main')
@section('title', 'User Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">User Management</h5>
    <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2">
                <div class="col-auto flex-grow-1"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ $search }}"></div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button></div>
                @if($search)<div class="col-auto"><a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-danger">&times; Reset</a></div>@endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 small">
                <thead class="table-light">
                    <tr><th>No</th><th>Nama</th><th>Username</th><th>Roles</th><th>Status</th><th class="text-center">Action</th></tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->username }}</td>
                        <td>
                            @if($row->assigned_roles && count($row->assigned_roles) > 0)
                                @foreach($row->assigned_roles as $role)
                                    <span class="badge bg-info text-dark me-1 mb-1">
                                        {{ $role['role_name'] }}
                                        <form action="{{ route('user.remove-role', ['userId' => $row->id, 'roleplayId' => $role['roleplay_id']]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role ini dari user?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-close btn-close-white ms-1" style="font-size:0.5rem;" title="Hapus role"></button>
                                        </form>
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $row->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $row->is_active ? 'Aktif' : 'Non' }}</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger btn-sm-table" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal-user" data-action="{{ route('user.destroy', $row->id) }}"><i class="bi bi-trash"></i></button>
                            <button class="btn btn-sm btn-outline-success btn-sm-table" title="Tambah Role" data-bs-toggle="modal" data-bs-target="#addRoleModal-{{ $row->id }}"><i class="bi bi-shield-plus"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
        <div class="px-3 py-2 border-top">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal-user" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Konfirmasi Hapus</h6><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body small">Yakin hapus user ini? Data tidak dapat dikembalikan.</div><div class="modal-footer"><button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button><form id="deleteForm-user" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form></div></div></div>
</div>

{{-- Add Role Modals (one per user) --}}
@foreach($data as $row)
<div class="modal fade" id="addRoleModal-{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.assign-role') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $row->id }}">
                <div class="modal-header"><h6 class="modal-title">Tambah Role - {{ $row->name }}</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <select name="role_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($availableRoles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    const deleteModalUser = document.getElementById('deleteModal-user');
    deleteModalUser.addEventListener('show.bs.modal', function(e) { document.getElementById('deleteForm-user').action = e.relatedTarget.getAttribute('data-action'); });
</script>
@endpush
@endsection
