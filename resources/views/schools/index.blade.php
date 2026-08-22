@extends('layouts.app')

@section('title', 'Unit Sekolah Yayasan - SekolahKu')
@section('page_title', 'Unit Sekolah Yayasan')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom">
        <div class="min-w-0 w-100">
            <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-2 bg-primary-subtle text-primary border border-primary-subtle mb-2 text-break" style="font-size: 0.78rem; max-width: 100%;">
                <i class="bi bi-diagram-3-fill flex-shrink-0"></i>
                <span class="fw-semibold text-truncate">Jaringan Yayasan: {{ $tenant->name }}</span>
            </div>
            <h4 class="fw-bold m-0 text-dark" style="font-family: 'Outfit';">Daftar Unit Sekolah Naungan</h4>
            <p class="text-muted small m-0 mt-1">Kelola multi-unit sekolah, alihkan konteks aktif, dan pantau ekosistem di bawah Yayasan.</p>
        </div>
        
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto flex-shrink-0">
            @php
                $canAdd = $tenant->canAddSchool();
                $count = $schools->count();
            @endphp
            <div class="px-3 py-2 bg-light rounded-3 border text-start text-sm-end">
                <span class="small text-muted d-block" style="font-size: 0.72rem;">Kuota Lisensi Unit:</span>
                <span class="fw-bold text-dark fs-6">{{ $count }} / {{ $maxSchools == 0 ? 'Unlimited' : $maxSchools }} Unit</span>
            </div>

            @if($canAdd)
                <button class="btn btn-primary px-3 py-2 rounded-3 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-1.5" data-bs-toggle="modal" data-bs-target="#addSchoolModal" style="min-height: 42px;">
                    <i class="bi bi-plus-lg"></i> Tambah Unit
                </button>
            @else
                <button class="btn btn-secondary px-3 py-2 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-1.5" disabled title="Batas unit sekolah lisensi telah tercapai" style="min-height: 42px;">
                    <i class="bi bi-lock-fill"></i> Kuota Penuh
                </button>
            @endif
        </div>
    </div>

    <!-- Schools Grid -->
    <div class="row g-3 g-lg-4 align-items-stretch">
        @foreach($schools as $s)
            @php
                $isActiveContext = (Auth::user()->school_id == $s->id);
            @endphp
            <div class="col-12 col-md-6 col-xl-4 d-flex">
                <div class="card h-100 w-100 rounded-3 border p-3 p-sm-3.5 d-flex flex-column justify-content-between bg-white position-relative {{ $isActiveContext ? 'border-primary shadow-xs' : 'border-slate-200' }}"
                     style="{{ $isActiveContext ? 'border-width: 1.5px !important; border-top: 3.5px solid var(--sk-primary) !important;' : '' }}">
                    
                    <div>
                        <!-- Top Badges -->
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2.5 flex-wrap">
                            <span class="badge bg-light text-secondary border rounded-2 px-2.5 py-1 font-monospace" style="font-size: 0.75rem;">
                                <i class="bi bi-mortarboard me-1 text-primary"></i> {{ $s->jenjang }}
                            </span>
                            @if($isActiveContext)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sedang Aktif
                                </span>
                            @else
                                <span class="badge bg-light text-muted border rounded-2 px-2 py-0.5" style="font-size: 0.72rem;">
                                    ID: {{ $s->code ?? $s->id }}
                                </span>
                            @endif
                        </div>

                        <!-- School Name & Address -->
                        <h5 class="fw-bold text-dark mt-1 mb-1 text-break" style="font-family: 'Outfit'; font-size: 1.15rem; line-height: 1.35;">
                            {{ $s->name }}
                        </h5>
                        <p class="small text-muted mb-3 text-break" style="font-size: 0.82rem; line-height: 1.4;">
                            <i class="bi bi-geo-alt-fill me-1 text-danger opacity-75"></i> {{ $s->address ?: 'Alamat sekolah belum diatur' }}
                        </p>

                        <!-- Stats Row -->
                        <div class="bg-light-subtle rounded-3 p-2.5 mb-3 border">
                            <div class="row g-0 text-center align-items-center">
                                <div class="col-4 border-end">
                                    <span class="fw-bold text-dark d-block" style="font-size: 1.15rem;">{{ $s->siswas_count }}</span>
                                    <span class="text-muted text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.3px;">Siswa</span>
                                </div>
                                <div class="col-4 border-end">
                                    <span class="fw-bold text-dark d-block" style="font-size: 1.15rem;">{{ $s->gurus_count }}</span>
                                    <span class="text-muted text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.3px;">Guru</span>
                                </div>
                                <div class="col-4">
                                    <span class="fw-bold text-dark d-block" style="font-size: 1.15rem;">{{ $s->rombels_count }}</span>
                                    <span class="text-muted text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.3px;">Rombel</span>
                                </div>
                            </div>
                        </div>

                        <!-- Leadership Meta -->
                        <div class="p-2.5 rounded-3 bg-light border mb-3 text-secondary" style="font-size: 0.78rem;">
                            <div class="d-flex align-items-center gap-1.5 text-truncate mb-1">
                                <i class="bi bi-person-badge text-primary flex-shrink-0"></i>
                                <span class="text-muted">Kepala Sekolah:</span>
                                <strong class="text-dark text-truncate">{{ $s->kepala_sekolah_nama ?: '-' }}</strong>
                            </div>
                            <div class="d-flex align-items-center gap-1.5 text-truncate">
                                <i class="bi bi-cash-stack text-success flex-shrink-0"></i>
                                <span class="text-muted">Bendahara:</span>
                                <strong class="text-dark text-truncate">{{ $s->bendahara_nama ?: '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="pt-1 mt-auto">
                        @if($isActiveContext)
                            <button class="btn btn-outline-success btn-sm w-100 rounded-3 fw-bold disabled d-flex align-items-center justify-content-center gap-1.5" style="min-height: 40px; opacity: 1; background-color: #f0fdf4; border-color: #bbf7d0; color: #15803d;">
                                <i class="bi bi-check2-circle fs-6"></i> Unit Aktif Digunakan
                            </button>
                        @else
                            <form action="{{ route('schools.switch', $s->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-1.5 shadow-xs" style="min-height: 40px;">
                                    <i class="bi bi-box-arrow-in-right fs-6"></i> Beralih & Kelola Unit
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Unit Sekolah -->
<div class="modal fade" id="addSchoolModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-building-add text-primary me-2"></i>Daftarkan Unit Sekolah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('schools.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold text-secondary small">Nama Unit Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light" placeholder="Contoh: SD Islam Al-Falah 02" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold text-secondary small">NPSN (Opsional)</label>
                            <input type="text" name="npsn" class="form-control bg-light" placeholder="20109988" style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Jenjang Pendidikan <span class="text-danger">*</span></label>
                            <select name="jenjang" class="form-select bg-light" required style="min-height: 42px;">
                                <option value="PAUD/TK/RA">PAUD / TK / RA</option>
                                <option value="SD/MI">SD / MI</option>
                                <option value="SMP/MTs">SMP / MTs</option>
                                <option value="SMA/SMK/MA">SMA / SMK / MA</option>
                                <option value="Pesantren">Pondok Pesantren</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">No. HP / Telepon Unit</label>
                            <input type="text" name="phone" class="form-control bg-light" placeholder="081234567890" style="min-height: 42px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Alamat Sekolah</label>
                            <textarea name="address" rows="2" class="form-control bg-light" placeholder="Jl. Raya Pendidikan No..."></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Kepala Sekolah</label>
                            <input type="text" name="kepala_sekolah_nama" class="form-control bg-light" placeholder="Dra. Hj. Siti..." style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Bendahara Sekolah</label>
                            <input type="text" name="bendahara_nama" class="form-control bg-light" placeholder="Ahmadi, S.E." style="min-height: 42px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">
                        <i class="bi bi-check-lg me-1"></i> Simpan Unit Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
