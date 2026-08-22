@extends('layouts.app')

@section('title', 'Manajemen Role & Hak Akses - SekolahKu')
@section('page_title', 'Hak Akses & Role (RBAC)')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold m-0 text-dark">Matriks Hak Akses (Role Has Permission)</h4>
        <p class="text-muted small m-0">Sesuaikan izin akses modul & fitur untuk setiap peran pengguna dalam ekosistem sekolah.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
        <button class="btn btn-outline-primary rounded-3 px-3 fw-semibold flex-fill flex-sm-grow-0" data-bs-toggle="modal" data-bs-target="#newPermissionModal" style="min-height: 40px;">
            <i class="bi bi-key-fill me-1"></i> Tambah Permission
        </button>
        <button class="btn btn-primary rounded-3 px-3 fw-bold shadow-xs flex-fill flex-sm-grow-0" data-bs-toggle="modal" data-bs-target="#newRoleModal" style="min-height: 40px;">
            <i class="bi bi-shield-plus me-1"></i> Tambah Peran Baru
        </button>
    </div>
</div>

<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <!-- Role Tabs Nav -->
    <ul class="nav nav-pills nav-fill bg-light-subtle p-1.5 rounded-3 mb-4 border" id="roleTabs" role="tablist">
        @foreach($roles as $idx => $role)
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold rounded-2 {{ $idx == 0 ? 'active' : '' }}" id="tab-{{ $role->id }}" data-bs-toggle="tab" data-bs-target="#role-pane-{{ $role->id }}" type="button" role="tab" style="min-height: 40px;">
                    <i class="bi bi-shield-check me-1"></i> {{ $role->name }}
                    <span class="badge bg-secondary-subtle text-dark ms-1 rounded-2" style="font-size:0.7rem;">{{ $role->permissions->count() }} Izin</span>
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

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 p-3 bg-light-subtle rounded-3 border mb-4">
                        <div>
                            <h5 class="fw-bold text-dark m-0">Konfigurasi Hak Akses: <span class="text-primary">{{ $role->name }}</span></h5>
                            <small class="text-muted">Centang izin yang ingin diberikan kepada pengguna dengan peran <strong>{{ $role->name }}</strong>.</small>
                        </div>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold shadow-xs w-100 w-sm-auto" style="min-height: 40px;">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses
                        </button>
                    </div>

                    <div class="row g-3 g-md-4">
                        @foreach($permissionGroups as $groupTitle => $groupItems)
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3 bg-white h-100 shadow-2xs">
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-folder-check text-primary me-2"></i>{{ $groupTitle }}</h6>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="font-size:0.75rem;" onclick="toggleGroupCheckboxes(this)">Pilih Semua</button>
                                    </div>

                                    <div class="group-checkboxes">
                                        @foreach($groupItems as $permKey => $permLabel)
                                            @php
                                                $hasPerm = $role->permissions->contains('name', $permKey);
                                            @endphp
                                            <div class="form-check py-1">
                                                <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permKey }}" id="chk_{{ $role->id }}_{{ $permKey }}" {{ $hasPerm ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="chk_{{ $role->id }}_{{ $permKey }}">
                                                    {{ $permLabel }}
                                                    <span class="badge bg-light text-muted border font-monospace ms-1 rounded-2" style="font-size:0.65rem;">{{ $permKey }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2.5 rounded-3 fw-bold shadow-xs w-100 w-sm-auto" style="min-height: 44px;">
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Peran (Role) Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Peran / Role <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-light" placeholder="Contoh: Guru BK, Kurikulum" required style="min-height: 42px;">
                        <small class="text-muted" style="font-size:0.75rem;">Nama peran yang akan diberikan kepada pengguna.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">Buat Peran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal New Permission -->
<div class="modal fade" id="newPermissionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Hak Akses (Permission) Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Permission (Slug) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-light" placeholder="Contoh: manage-inventory" required style="min-height: 42px;">
                        <small class="text-muted" style="font-size:0.75rem;">Gunakan format huruf kecil dengan tanda strip (-).</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">Buat Permission</button>
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
