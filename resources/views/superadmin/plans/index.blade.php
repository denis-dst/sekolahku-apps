@extends('layouts.app')

@section('title', 'Paket & Fitur Layanan - SekolahKu')
@section('page_title', 'Paket & Fitur Layanan')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold m-0 text-dark">Daftar Paket Langganan SaaS</h4>
        <p class="text-muted small m-0">Atur batasan siswa, harga, dan fitur aktif untuk setiap tier paket.</p>
    </div>
    <button class="btn btn-primary rounded-3 px-3 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newPlanModal" style="min-height: 40px;">
        <i class="bi bi-plus-lg me-1"></i> Buat Paket Kustom
    </button>
</div>

<!-- Plan Cards Row -->
<div class="row g-3 g-md-4 mb-4 align-items-stretch">
    @foreach($plans as $p)
        <div class="col-12 col-md-4 d-flex">
            <div class="card-custom h-100 w-100 p-3.5 p-sm-4 bg-white border-top border-3 {{ $p->code == 'pro' ? 'border-success' : ($p->code == 'enterprise' ? 'border-primary' : 'border-secondary') }} d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-dark m-0">{{ $p->name }}</h5>
                            <span class="badge bg-light text-dark border text-uppercase rounded-2 mt-1" style="font-size:0.7rem;">Kode: {{ $p->code }}</span>
                        </div>
                        @if($p->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-2">Non-Aktif</span>
                        @endif
                    </div>

                    <div class="my-3 p-3 bg-light-subtle rounded-3 border text-center">
                        <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Harga Langganan</div>
                        <h3 class="fw-bold text-dark m-0" style="font-size: clamp(1.3rem, 2vw, 1.6rem);">
                            @if($p->price == 0)
                                Gratis
                            @else
                                Rp {{ number_format($p->price, 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">/ bln</span>
                            @endif
                        </h3>
                    </div>

                    <div class="mb-3 small">
                        <div class="d-flex justify-content-between py-1.5 border-bottom">
                            <span class="text-muted">Batas Maksimal Siswa:</span>
                            <strong class="text-dark">{{ $p->max_siswas == 0 ? 'Unlimited' : number_format($p->max_siswas) . ' Siswa' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1.5 border-bottom">
                            <span class="text-muted">Batas Unit Sekolah:</span>
                            <strong class="text-dark">{{ $p->max_schools }} Sekolah</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1.5 border-bottom">
                            <span class="text-muted">Sekolah Terdaftar:</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2">{{ $p->tenants_count }} Tenant</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="fw-semibold text-dark small mb-2">Daftar Fitur Termasuk:</div>
                        <ul class="list-unstyled small m-0">
                            @foreach($availableFeatures as $fKey => $fLabel)
                                <li class="py-1 d-flex align-items-center {{ $p->hasFeature($fKey) ? 'text-dark' : 'text-muted text-decoration-line-through opacity-50' }}">
                                    @if($p->hasFeature($fKey))
                                        <i class="bi bi-check-circle-fill text-success me-2 flex-shrink-0"></i>
                                    @else
                                        <i class="bi bi-x-circle me-2 text-secondary flex-shrink-0"></i>
                                    @endif
                                    <span>{{ $fLabel }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="mt-auto pt-2">
                    <button class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#editPlanModal{{ $p->id }}" style="min-height: 38px;">
                        <i class="bi bi-pencil-square me-1"></i> Edit Paket & Fitur
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Plan -->
        <div class="modal fade" id="editPlanModal{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark">Edit Paket Langganan — {{ $p->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.plans.update', $p->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-3 p-sm-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Nama Paket <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control bg-light" value="{{ $p->name }}" required style="min-height: 42px;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Harga Per Bulan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control bg-light" value="{{ (int)$p->price }}" required style="min-height: 42px;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Batas Siswa (0 = Unlimited) <span class="text-danger">*</span></label>
                                    <input type="number" name="max_siswas" class="form-control bg-light" value="{{ $p->max_siswas }}" required style="min-height: 42px;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary small">Batas Unit Sekolah <span class="text-danger">*</span></label>
                                    <input type="number" name="max_schools" class="form-control bg-light" value="{{ $p->max_schools }}" required style="min-height: 42px;">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary small">Deskripsi Singkat</label>
                                    <textarea name="description" class="form-control bg-light" rows="2">{{ $p->description }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary small">Aktifkan Fitur-Fitur Paket Ini:</label>
                                    <div class="row g-2 border p-3 rounded-3 bg-light-subtle">
                                        @foreach($availableFeatures as $fKey => $fLabel)
                                            <div class="col-12 col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features[]" value="{{ $fKey }}" id="f_{{ $p->id }}_{{ $fKey }}" {{ $p->hasFeature($fKey) ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold text-dark" for="f_{{ $p->id }}_{{ $fKey }}">
                                                        {{ $fLabel }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="active_{{ $p->id }}" {{ $p->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-dark small" for="active_{{ $p->id }}">Status Paket Aktif & Bisa Dipilih</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal New Plan -->
<div class="modal fade" id="newPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Paket Kustom Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light" placeholder="Contoh: Paket Pro Plus" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Kode Unik (Slug) <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control bg-light" placeholder="pro_plus" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Harga Bulanan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control bg-light" placeholder="250000" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Batas Siswa (0 = Unlimited) <span class="text-danger">*</span></label>
                            <input type="number" name="max_siswas" class="form-control bg-light" value="0" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Batas Unit Sekolah <span class="text-danger">*</span></label>
                            <input type="number" name="max_schools" class="form-control bg-light" value="1" required style="min-height: 42px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Pilih Fitur Terkait:</label>
                            <div class="row g-2 border p-3 rounded-3 bg-light-subtle">
                                @foreach($availableFeatures as $fKey => $fLabel)
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="features[]" value="{{ $fKey }}" id="new_f_{{ $fKey }}" checked>
                                            <label class="form-check-label small fw-semibold text-dark" for="new_f_{{ $fKey }}">
                                                {{ $fLabel }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">Buat Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
