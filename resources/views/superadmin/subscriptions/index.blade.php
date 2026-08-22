@extends('layouts.app')

@section('title', 'Manajemen Langganan Sekolah - SekolahKu')
@section('page_title', 'Langganan Sekolah')

@section('content')
@if(isset($pendingCount) && $pendingCount > 0)
    <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-dark p-3 rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-warning rounded-2 text-dark d-flex align-items-center justify-content-center">
                <i class="bi bi-bell-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-dark">Terdapat {{ $pendingCount }} Pendaftaran Yayasan / Sekolah Baru Menunggu Persetujuan (ACC)!</h6>
                <small class="text-muted">Tinjau permohonan paket lisensi dan lakukan aktivasi langganan dengan 1-klik.</small>
            </div>
        </div>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-2 shadow-xs" style="min-height: 36px;">
            <i class="bi bi-filter me-1"></i> Tampilkan Permohonan Pending
        </a>
    </div>
@endif

<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold m-0 text-dark">Langganan Sekolah & Yayasan</h4>
            <p class="text-muted small m-0">Kelola penetapan paket, durasi aktif, dan persetujuan (ACC) pendaftaran baru.</p>
        </div>
        <a href="{{ route('admin.system.migrate') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold d-flex align-items-center gap-1.5" onclick="return confirm('Jalankan migrasi dan seeder database sekarang?')" title="Jalankan migrasi database jika ada tabel/fitur baru" style="min-height: 38px;">
            <i class="bi bi-arrow-repeat"></i> Sinkron Database & Paket
        </a>
    </div>

    <!-- Filter Header -->
    <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row g-2 align-items-end mb-4">
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold text-secondary small">Cari Nama Sekolah / Tenant</label>
            <input type="text" name="search" class="form-control bg-light" placeholder="Nama sekolah / kode..." value="{{ request('search') }}" style="min-height: 42px;">
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label fw-semibold text-secondary small">Filter Paket</label>
            <select name="plan_id" class="form-select bg-light" onchange="this.form.submit()" style="min-height: 42px;">
                <option value="">-- Semua Paket --</option>
                @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label fw-semibold text-secondary small">Filter Status</label>
            <select name="status" class="form-select bg-light" onchange="this.form.submit()" style="min-height: 42px;">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu ACC (Pending)</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold shadow-xs" style="min-height: 42px;">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Nama Tenant / Yayasan</th>
                    <th>Paket Diminta / Aktif</th>
                    <th>Unit & Siswa</th>
                    <th>Masa Berlaku</th>
                    <th>Status Langganan</th>
                    <th class="text-center">Aksi Superadmin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $idx => $t)
                    @php
                        $schoolCount = $t->schools->count();
                        $firstSchool = $t->schools->first();
                        $totalSiswa = $t->schools->sum(fn($s) => $s->siswas->count());
                        $plan = $t->subscriptionPlan;
                        $maxSiswa = $plan?->max_siswas ?? 50;
                        $maxSchools = $plan?->max_schools ?? 1;
                    @endphp
                    <tr>
                        <td class="text-muted fw-semibold">{{ $tenants->firstItem() + $idx }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $t->name }}</div>
                            <small class="text-muted"><i class="bi bi-building me-1"></i> {{ $firstSchool?->name ?? 'Sekolah' }} (Kode: {{ $t->code }})</small>
                            @if($t->users->first())
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-person me-1"></i> Admin: {{ $t->users->first()->name }} ({{ $t->users->first()->email }})</div>
                            @endif
                        </td>
                        <td>
                            @if($plan)
                                @php
                                    $badgeClass = match($plan->code) {
                                        'pro' => 'bg-success-subtle text-success border border-success-subtle',
                                        'enterprise' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        default => 'bg-secondary-subtle text-secondary border border-secondary-subtle'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2.5 py-1 rounded-2"><i class="bi bi-box-seam me-1"></i> {{ $plan->name }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-2">Free</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $schoolCount }} Unit / {{ $totalSiswa }} Siswa</div>
                            <small class="text-muted">Limit: {{ $maxSchools == 0 ? 'Unlimited' : $maxSchools . ' Unit' }} | {{ $maxSiswa == 0 ? 'Unlimited' : $maxSiswa . ' Siswa' }}</small>
                        </td>
                        <td>
                            @if($t->subscription_status == 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-2"><i class="bi bi-clock me-1"></i> Belum Diaktifkan</span>
                            @elseif($t->subscription_expires_at)
                                <div class="fw-semibold text-dark">{{ $t->subscription_expires_at->format('d/m/Y') }}</div>
                                @if($t->subscription_expires_at->isPast())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2">Kadaluarsa {{ $t->subscription_expires_at->diffForHumans() }}</span>
                                @else
                                    <small class="text-success"><i class="bi bi-clock me-1"></i> Sisa {{ $t->subscription_expires_at->diffInDays(now()) }} hari</small>
                                @endif
                            @else
                                <span class="text-muted small">Aktif Lifetime</span>
                            @endif
                        </td>
                        <td>
                            @if($t->subscription_status == 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-2"><i class="bi bi-hourglass-split me-1"></i> Menunggu ACC</span>
                            @elseif($t->subscription_status == 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                            @elseif($t->subscription_status == 'suspended')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2"><i class="bi bi-slash-circle me-1"></i> Ditangguhkan</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-2"><i class="bi bi-exclamation-triangle me-1"></i> Expired</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center flex-wrap">
                                @if($t->subscription_status == 'pending')
                                    <button class="btn btn-sm btn-success rounded-2 fw-bold shadow-xs px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#approveModal{{ $t->id }}" style="min-height: 34px;">
                                        <i class="bi bi-check-circle-fill me-1"></i> ACC
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-outline-primary rounded-2 fw-semibold px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#assignPlanModal{{ $t->id }}" style="min-height: 34px;">
                                    <i class="bi bi-gear-fill me-1"></i> Kelola
                                </button>

                                <form action="{{ route('admin.subscriptions.toggle', $t->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $t->subscription_status == 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-2 px-2.5 py-1" title="Toggle Status" style="min-height: 34px;">
                                        <i class="bi {{ $t->subscription_status == 'active' ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Approve Quick ACC -->
                    @if($t->subscription_status == 'pending')
                    <div class="modal fade" id="approveModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i>ACC & Aktivasi Lisensi Yayasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.subscriptions.approve', $t->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-3 p-sm-4">
                                        <div class="p-3 bg-light-subtle rounded-3 mb-3 border">
                                            <div class="fw-bold text-dark">{{ $t->name }}</div>
                                            <div class="small text-muted">Paket Permintaan: <strong>{{ $plan?->name ?? 'Pro' }}</strong></div>
                                            <div class="small text-muted">Admin: {{ $t->users->first()?->name }} ({{ $t->users->first()?->email }})</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small">Durasi Masa Aktif Lisensi</label>
                                            <select name="duration_months" class="form-select bg-light" style="min-height: 42px;">
                                                <option value="1">1 Bulan</option>
                                                <option value="3">3 Bulan</option>
                                                <option value="6">6 Bulan</option>
                                                <option value="12" selected>1 Tahun (12 Bulan)</option>
                                                <option value="24">2 Tahun (24 Bulan)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small">Catatan / Referensi Pembayaran (Opsional)</label>
                                            <input type="text" name="notes" class="form-control bg-light" placeholder="Contoh: Bukti transfer BCA An. Yayasan" style="min-height: 42px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                                        <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">
                                            <i class="bi bi-check-lg me-1"></i> Konfirmasi & Aktifkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Modal Assign / Change Subscription -->
                    <div class="modal fade" id="assignPlanModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Kelola Langganan — {{ $t->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.subscriptions.update', $t->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-3 p-sm-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Pilih Paket Langganan</label>
                                                <select name="subscription_plan_id" class="form-select bg-light" required style="min-height: 42px;">
                                                    @foreach($plans as $p)
                                                        <option value="{{ $p->id }}" {{ $t->subscription_plan_id == $p->id ? 'selected' : '' }}>
                                                             {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }}/bln)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Status Langganan</label>
                                                <select name="subscription_status" class="form-select bg-light" style="min-height: 42px;">
                                                    <option value="pending" {{ $t->subscription_status == 'pending' ? 'selected' : '' }}>Menunggu ACC (Pending)</option>
                                                    <option value="active" {{ $t->subscription_status == 'active' ? 'selected' : '' }}>Aktif (Active)</option>
                                                    <option value="expired" {{ $t->subscription_status == 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                                                    <option value="suspended" {{ $t->subscription_status == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Tambah Durasi (Bulan)</label>
                                                <select name="duration_months" class="form-select bg-light" style="min-height: 42px;">
                                                    <option value="0">Tetap / Tanpa Perubahan Durasi</option>
                                                    <option value="1">+1 Bulan</option>
                                                    <option value="3">+3 Bulan</option>
                                                    <option value="6">+6 Bulan</option>
                                                    <option value="12">+1 Tahun (12 Bulan)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Atau Set Tanggal Kadaluarsa Spesifik</label>
                                                <input type="date" name="custom_expires_at" class="form-control bg-light" value="{{ $t->subscription_expires_at ? $t->subscription_expires_at->format('Y-m-d') : '' }}" style="min-height: 42px;">
                                                <small class="text-muted" style="font-size:0.75rem;">Kosongkan jika ingin seumur hidup / unlimited.</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold text-secondary small">Catatan Superadmin / Memo Pembayaran</label>
                                                <textarea name="notes" class="form-control bg-light" rows="3" placeholder="Contoh: Pembayaran tahunan via Transfer Bank manual No. Invoice #INV-202608-001">{{ $t->notes }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 40px;">Simpan Langganan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada data tenant / yayasan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $tenants->links() }}
    </div>
</div>
@endsection

