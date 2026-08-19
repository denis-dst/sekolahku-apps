@extends('layouts.app')

@section('title', 'Manajemen Langganan Sekolah - SekolahKu')
@section('page_title', 'Langganan Sekolah')

@section('content')
@if(isset($pendingCount) && $pendingCount > 0)
    <div class="alert alert-warning border-warning p-3 rounded-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-warning rounded-3 text-dark">
                <i class="bi bi-bell-fill fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-dark">Terdapat {{ $pendingCount }} Pendaftaran Yayasan / Sekolah Baru Menunggu Persetujuan (ACC)!</h6>
                <small class="text-muted">Tinjau permohonan paket lisensi dan lakukan aktivasi langganan dengan 1-klik.</small>
            </div>
        </div>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3">
            <i class="bi bi-filter me-1"></i> Tampilkan Permohonan Pending
        </a>
    </div>
@endif

<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold m-0 text-dark">Langganan Sekolah & Yayasan</h4>
            <p class="text-muted small m-0">Kelola penetapan paket, durasi aktif, dan persetujuan (ACC) pendaftaran baru.</p>
        </div>
        <a href="{{ route('admin.system.migrate') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold d-flex align-items-center gap-1.5" onclick="return confirm('Jalankan migrasi dan seeder database sekarang?')" title="Jalankan migrasi database jika ada tabel/fitur baru">
            <i class="bi bi-arrow-repeat"></i> Sinkron Database & Paket
        </a>
    </div>

    <!-- Filter Header -->
    <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold">Cari Nama Sekolah / Tenant</label>
            <input type="text" name="search" class="form-control" placeholder="Nama sekolah / kode..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label fw-semibold">Filter Paket</label>
            <select name="plan_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Paket --</option>
                @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label fw-semibold">Filter Status</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu ACC (Pending)</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
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
                    <tr class="{{ $t->subscription_status == 'pending' ? 'table-warning' : '' }}">
                        <td>{{ $tenants->firstItem() + $idx }}</td>
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
                                    $badgeStyle = match($plan->code) {
                                        'pro' => 'bg-success text-white',
                                        'enterprise' => 'bg-primary text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $badgeStyle }} px-3 py-2 fs-6"><i class="bi bi-box-seam me-1"></i> {{ $plan->name }}</span>
                            @else
                                <span class="badge bg-secondary">Belum Diatur (Free)</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $schoolCount }} Unit Sekolah / {{ $totalSiswa }} Siswa</div>
                            <small class="text-muted">Limit: {{ $maxSchools == 0 ? 'Unlimited' : $maxSchools . ' Unit' }} | {{ $maxSiswa == 0 ? 'Unlimited Siswa' : $maxSiswa . ' Siswa' }}</small>
                        </td>
                        <td>
                            @if($t->subscription_status == 'pending')
                                <span class="badge bg-warning text-dark border"><i class="bi bi-clock me-1"></i> Belum Diaktifkan</span>
                            @elseif($t->subscription_expires_at)
                                <div class="fw-semibold text-dark">{{ $t->subscription_expires_at->format('d/m/Y') }}</div>
                                @if($t->subscription_expires_at->isPast())
                                    <span class="badge bg-danger-subtle text-danger border">Kadaluarsa {{ $t->subscription_expires_at->diffForHumans() }}</span>
                                @else
                                    <small class="text-success"><i class="bi bi-clock me-1"></i> Sisa {{ $t->subscription_expires_at->diffInDays(now()) }} hari lagi</small>
                                @endif
                            @else
                                <span class="text-muted small">Aktif Selamanya / Lifetime</span>
                            @endif
                        </td>
                        <td>
                            @if($t->subscription_status == 'pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu ACC</span>
                            @elseif($t->subscription_status == 'active')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                            @elseif($t->subscription_status == 'suspended')
                                <span class="badge bg-danger"><i class="bi bi-slash-circle me-1"></i> Ditangguhkan</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-exclamation-triangle me-1"></i> Expired</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center flex-wrap">
                                @if($t->subscription_status == 'pending')
                                    <button class="btn btn-sm btn-success rounded-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $t->id }}">
                                        <i class="bi bi-check-circle-fill me-1"></i> ACC / Setujui
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-primary rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#assignPlanModal{{ $t->id }}">
                                    <i class="bi bi-gear-fill me-1"></i> Kelola
                                </button>

                                <form action="{{ route('admin.subscriptions.toggle', $t->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $t->subscription_status == 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-3" title="Toggle Status">
                                        <i class="bi {{ $t->subscription_status == 'active' ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Approve Quick ACC -->
                    @if($t->subscription_status == 'pending')
                    <div class="modal fade" id="approveModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i>ACC & Aktivasi Lisensi Yayasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.subscriptions.approve', $t->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="p-3 bg-light rounded-3 mb-3 border">
                                            <div class="fw-bold text-dark">{{ $t->name }}</div>
                                            <div class="small text-muted">Paket Permintaan: <strong>{{ $plan?->name ?? 'Pro' }}</strong></div>
                                            <div class="small text-muted">Admin: {{ $t->users->first()?->name }} ({{ $t->users->first()?->email }})</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Durasi Masa Aktif Lisensi</label>
                                            <select name="duration_months" class="form-select">
                                                <option value="1">1 Bulan</option>
                                                <option value="3">3 Bulan</option>
                                                <option value="6">6 Bulan</option>
                                                <option value="12" selected>1 Tahun (12 Bulan)</option>
                                                <option value="24">2 Tahun (24 Bulan)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Catatan / Referensi Pembayaran (Opsional)</label>
                                            <input type="text" name="notes" class="form-control" placeholder="Contoh: Bukti transfer BCA An. Yayasan">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold">
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
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Kelola Langganan — {{ $t->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.subscriptions.update', $t->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Pilih Paket Langganan</label>
                                                <select name="subscription_plan_id" class="form-select form-select-lg" required>
                                                    @foreach($plans as $p)
                                                        <option value="{{ $p->id }}" {{ $t->subscription_plan_id == $p->id ? 'selected' : '' }}>
                                                             {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }}/bln)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Status Langganan</label>
                                                <select name="subscription_status" class="form-select form-select-lg">
                                                    <option value="pending" {{ $t->subscription_status == 'pending' ? 'selected' : '' }}>Menunggu ACC (Pending)</option>
                                                    <option value="active" {{ $t->subscription_status == 'active' ? 'selected' : '' }}>Aktif (Active)</option>
                                                    <option value="expired" {{ $t->subscription_status == 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                                                    <option value="suspended" {{ $t->subscription_status == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Tambah Durasi (Bulan)</label>
                                                <select name="duration_months" class="form-select">
                                                    <option value="0">Tetap / Tanpa Perubahan Durasi</option>
                                                    <option value="1">+1 Bulan</option>
                                                    <option value="3">+3 Bulan</option>
                                                    <option value="6">+6 Bulan</option>
                                                    <option value="12">+1 Tahun (12 Bulan)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Atau Set Tanggal Kadaluarsa Spesifik</label>
                                                <input type="date" name="custom_expires_at" class="form-control" value="{{ $t->subscription_expires_at ? $t->subscription_expires_at->format('Y-m-d') : '' }}">
                                                <small class="text-muted" style="font-size:0.75rem;">Kosongkan jika ingin berlangganan seumur hidup / unlimited.</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Catatan Superadmin / Memo Pembayaran</label>
                                                <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Pembayaran tahunan via Transfer Bank manual No. Invoice #INV-202608-001">{{ $t->notes }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Langganan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data tenant / yayasan terdaftar.</td>
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

