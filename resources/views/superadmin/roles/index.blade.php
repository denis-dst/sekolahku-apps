@extends('layouts.app')

@section('title', 'Manajemen Peran & Hak Akses (Role Has Permission) - SekolahKu')
@section('page_title', 'Manajemen Hak Akses & Peran Pengguna (RBAC)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold m-0 text-dark">Matriks Hak Akses (Role Has Permission)</h4>
        <p class="text-muted small m-0">Sesuaikan izin akses modul & fitur untuk setiap peran pengguna dalam ekosistem sekolah.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary rounded-3 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#newPermissionModal">
            <i class="bi bi-key-fill me-1"></i> Tambah Permission
        </button>
        <button class="btn btn-primary rounded-3 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#newRoleModal">
            <i class="bi bi-shield-plus me-1"></i> Tambah Peran (Role) Baru
        </button>
    </div>
</div>

<div class="card-custom p-4 mb-4">
    <!-- Role Tabs Nav -->
    <ul class="nav nav-pills nav-fill bg-light p-2 rounded-3 mb-4 border" id="roleTabs" role="tablist">
        @foreach($roles as $idx => $role)
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold rounded-3 {{ $idx == 0 ? 'active' : '' }}" id="tab-{{ $role->id }}" data-bs-toggle="tab" data-bs-target="#role-pane-{{ $role->id }}" type="button" role="tab">
                    <i class="bi bi-shield-check me-1"></i> {{ $role->name }}
                    <span class="badge bg-secondary-subtle text-dark ms-1" style="font-size:0.7rem;">{{ $role->permissions->count() }} Hak Akses</span>
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Role Tab Panes -->
    <div class="tab-content" id="roleTabContent">
        @foreach($roles as $idx => $role)
            <div class="tab-pane fade {{ $idx == 0 ? 'show active' : '' }}" id="role-pane-{{ $role->id }}" role="tabpanel">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border mb-4">
                        <div>
                            <h5 class="fw-bold text-dark m-0">Konfigurasi Hak Akses: <span class="text-primary">{{ $role->name }}</span></h5>
                            <small class="text-muted">Centang izin yang ingin diberikan kepada pengguna dengan peran <strong>{{ $role->name }}</strong>.</small>
                        </div>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses {{ $role->name }}
                        </button>
                    </div>

                    <div class="row g-4">
                        @foreach($permissionGroups as $groupTitle => $groupItems)
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3 bg-white h-100 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-folder-check text-primary me-2"></i>{{ $groupTitle }}</h6>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="font-size:0.75rem;" onclick="toggleGroupCheckboxes(this)">Pilih Semua</button>
                                    </div>

                                    <div class="group-checkboxes">
                                        @foreach($groupItems as $permKey => $permLabel)
                                            @php
                                                $hasPerm = $role->hasPermissionTo($permKey);
                                            @endphp
                                            <div class="form-check py-1">
                                                <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permKey }}" id="chk_{{ $role->id }}_{{ $permKey }}" {{ $hasPerm ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="chk_{{ $role->id }}_{{ $permKey }}">
                                                    {{ $permLabel }}
                                                    <span class="badge bg-light text-muted border font-monospace ms-1" style="font-size:0.65rem;">{{ $permKey }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses {{ $role->name }}
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal New Role -->
<div class="modal fade" id="newRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Peran (Role) Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Peran / Role</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Guru BK, Kurikulum" required>
                        <small class="text-muted" style="font-size:0.75rem;">Nama peran yang akan diberikan kepada pengguna.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Buat Peran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal New Permission -->
<div class="modal fade" id="newPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Hak Akses (Permission) Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Permission (Slug)</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: manage-inventory" required>
                        <small class="text-muted" style="font-size:0.75rem;">Gunakan format huruf kecil dengan tanda strip (-).</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Buat Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleGroupCheckboxes(btn) {
        const container = btn.closest('.border').querySelector('.group-checkboxes');
        const checkboxes = container.querySelectorAll('.perm-checkbox');
        const allChecked = Array.from(checkboxes).every(c => c.checked);

        checkboxes.forEach(c => c.checked = !allChecked);
        btn.textContent = allChecked ? 'Pilih Semua' : 'Batal Semua';
    }
</script>
@endpush
@endsection
