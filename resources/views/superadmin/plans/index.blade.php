@extends('layouts.app')

@section('title', 'Paket & Fitur Layanan - SekolahKu')
@section('page_title', 'Paket & Fitur Layanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold m-0 text-dark">Daftar Paket Langganan SaaS</h4>
        <p class="text-muted small m-0">Atur batasan siswa, harga, dan fitur aktif untuk setiap tier paket.</p>
    </div>
    <button class="btn btn-primary rounded-3 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#newPlanModal">
        <i class="bi bi-plus-lg me-1"></i> Buat Paket Kustom Baru
    </button>
</div>

<!-- Plan Cards Row -->
<div class="row g-4 mb-4">
    @foreach($plans as $p)
        <div class="col-12 col-md-4">
            <div class="card-custom h-100 p-4 border-top border-4 {{ $p->code == 'pro' ? 'border-success' : ($p->code == 'enterprise' ? 'border-primary' : 'border-secondary') }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="fw-bold text-dark m-0">{{ $p->name }}</h5>
                        <span class="badge bg-light text-muted border text-uppercase" style="font-size:0.7rem;">Kode: {{ $p->code }}</span>
                    </div>
                    @if($p->is_active)
                        <span class="badge bg-success-subtle text-success border">Aktif</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border">Non-Aktif</span>
                    @endif
                </div>

                <div class="my-3 p-3 bg-light rounded-3 border text-center">
                    <div class="small text-muted text-uppercase fw-semibold">Harga Langganan</div>
                    <h3 class="fw-extrabold text-dark m-0">
                        @if($p->price == 0)
                            Gratis
                        @else
                            Rp {{ number_format($p->price, 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">/ bln</span>
                        @endif
                    </h3>
                </div>

                <div class="mb-3 small">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Batas Maksimal Siswa:</span>
                        <strong class="text-dark">{{ $p->max_siswas == 0 ? 'Unlimited (Tanpa Batas)' : number_format($p->max_siswas) . ' Siswa' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Batas Unit Sekolah:</span>
                        <strong class="text-dark">{{ $p->max_schools }} Sekolah</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Sekolah Terdaftar:</span>
                        <span class="badge bg-primary">{{ $p->tenants_count }} Tenant</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="fw-semibold text-dark small mb-2">Daftar Fitur Termasuk:</div>
                    <ul class="list-unstyled small m-0">
                        @foreach($availableFeatures as $fKey => $fLabel)
                            <li class="py-1 {{ $p->hasFeature($fKey) ? 'text-dark' : 'text-muted text-decoration-line-through opacity-50' }}">
                                @if($p->hasFeature($fKey))
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                @else
                                    <i class="bi bi-x-circle me-2 text-secondary"></i>
                                @endif
                                {{ $fLabel }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-auto">
                    <button class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#editPlanModal{{ $p->id }}">
                        <i class="bi bi-pencil-square me-1"></i> Edit Paket & Fitur
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Plan -->
        <div class="modal fade" id="editPlanModal{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Paket Langganan — {{ $p->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.plans.update', $p->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Nama Paket</label>
                                    <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Harga Per Bulan (Rp)</label>
                                    <input type="number" name="price" class="form-control" value="{{ (int)$p->price }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Batas Siswa (0 = Unlimited)</label>
                                    <input type="number" name="max_siswas" class="form-control" value="{{ $p->max_siswas }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Batas Unit Sekolah</label>
                                    <input type="number" name="max_schools" class="form-control" value="{{ $p->max_schools }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi Singkat</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $p->description }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Aktifkan Fitur-Fitur Paket Ini:</label>
                                    <div class="row g-2 border p-3 rounded-3 bg-light">
                                        @foreach($availableFeatures as $fKey => $fLabel)
                                            <div class="col-12 col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features[]" value="{{ $fKey }}" id="f_{{ $p->id }}_{{ $fKey }}" {{ $p->hasFeature($fKey) ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold" for="f_{{ $p->id }}_{{ $fKey }}">
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
                                        <label class="form-check-label fw-semibold" for="active_{{ $p->id }}">Status Paket Aktif & Bisa Dipilih</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal New Plan -->
<div class="modal fade" id="newPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Paket Kustom Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Paket</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Paket Pro Plus" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kode Unik (Slug)</label>
                            <input type="text" name="code" class="form-control" placeholder="pro_plus" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Harga Bulanan (Rp)</label>
                            <input type="number" name="price" class="form-control" placeholder="250000" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Batas Siswa (0 = Unlimited)</label>
                            <input type="number" name="max_siswas" class="form-control" value="0" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Batas Unit Sekolah</label>
                            <input type="number" name="max_schools" class="form-control" value="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pilih Fitur Terkait:</label>
                            <div class="row g-2 border p-3 rounded-3 bg-light">
                                @foreach($availableFeatures as $fKey => $fLabel)
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="features[]" value="{{ $fKey }}" id="new_f_{{ $fKey }}" checked>
                                            <label class="form-check-label small fw-semibold" for="new_f_{{ $fKey }}">
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
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Buat Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
