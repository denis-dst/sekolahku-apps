@extends('layouts.app')

@section('title', 'Data Siswa - SekolahKu')
@section('page_title', 'Manajemen Data Siswa')

@section('content')
<!-- Header Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Siswa Terdaftar</h5>
        <small class="text-muted">Kelola data induk siswa, penempatan rombongan belajar, dan kontak wali murid</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('siswa.template-excel') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1" title="Unduh format template Excel untuk pengisian data massal">
            <i class="bi bi-download"></i> Unduh Template Excel
        </a>
        <button class="btn btn-outline-success btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#importExcelModal">
            <i class="bi bi-file-earmark-excel-fill"></i> Import Excel
        </button>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#newSiswaModal">
            <i class="bi bi-person-plus-fill"></i> Tambah Siswa Baru
        </button>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card-custom p-3 mb-4">
    <form action="{{ route('siswa.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama siswa, NISN, NIK..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-4">
            <select name="rombel_id" class="form-select bg-light" onchange="this.form.submit()">
                <option value="">Semua Rombel / Kelas</option>
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'rombel_id']))
                <a href="{{ route('siswa.index') }}" class="btn btn-light border" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Siswa Card -->
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold text-dark"><i class="bi bi-table me-1 text-primary"></i> Data Induk Siswa</span>
        <span class="badge bg-light text-muted border">{{ $siswas->total() }} Total Siswa</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Nama Lengkap</th>
                    <th style="width: 120px;">NISN / NIK</th>
                    <th style="width: 60px;">JK</th>
                    <th>Rombel</th>
                    <th>Orang Tua / No. HP</th>
                    <th style="width: 100px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $idx => $s)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $siswas->firstItem() + $idx }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $s->nama_lengkap }}</div>
                            @if($s->nama_panggilan)
                                <small class="text-muted">Panggilan: {{ $s->nama_panggilan }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="font-monospace small text-dark">{{ $s->nisn ?: '-' }}</div>
                            <small class="text-muted font-monospace" style="font-size: 0.72rem;">NIK: {{ $s->nik ?: '-' }}</small>
                        </td>
                        <td>
                            @if($s->jenis_kelamin == 'L')
                                <span class="badge bg-primary-subtle text-primary fw-bold">L</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fw-bold">P</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                {{ $s->rombel->nama_rombel ?? 'Belum Ditentukan' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $s->nama_ortu ?: '-' }}</div>
                            @if($s->no_hp_ortu)
                                <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i> {{ $s->no_hp_ortu }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $s->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-1 text-secondary d-block mb-2"></i>
                            Belum ada data siswa yang ditemukan. Anda dapat menambahkan siswa manual atau menggunakan fitur <strong>Import Excel</strong>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $siswas->links() }}
    </div>
</div>

<!-- Modal Import Excel Siswa -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Import Data Siswa dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('siswa.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Instruction Alert -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Petunjuk Pengisian:</div>
                        <ul class="text-muted small ps-3 mb-2" style="font-size: 0.82rem;">
                            <li>Gunakan format template resmi agar susunan kolom sesuai.</li>
                            <li>Kolom <strong>Nama Lengkap*</strong> dan <strong>Jenis Kelamin* (L/P)</strong> wajib diisi.</li>
                            <li>Kolom <strong>Nama Rombel / Kelas</strong> otomatis dicocokkan dengan data rombel sekolah.</li>
                        </ul>
                        <a href="{{ route('siswa.template-excel') }}" class="btn btn-outline-success btn-sm rounded-2 w-100 fw-semibold">
                            <i class="bi bi-download me-1"></i> Unduh File Template Excel (.xlsx)
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Rombel Default (Opsional)</label>
                        <select name="fallback_rombel_id" class="form-select bg-light">
                            <option value="">-- Tetapkan jika kolom Rombel di Excel kosong --</option>
                            @foreach($rombels as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary small">Pilih File Excel / CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control bg-light" accept=".xlsx, .xls, .csv" required>
                        <small class="text-muted">Mendukung format .xlsx, .xls, atau .csv (Maksimal 10 MB)</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold">
                        <i class="bi bi-upload me-1"></i> Mulai Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal New Siswa Manual -->
<div class="modal fade" id="newSiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill text-primary me-2"></i>Tambah Data Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control bg-light" placeholder="Nama lengkap siswa" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Panggilan</label>
                            <input type="text" name="nama_panggilan" class="form-control bg-light" placeholder="Nama panggilan">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">NISN</label>
                            <input type="text" name="nisn" class="form-control bg-light" placeholder="0011223344">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">NIK</label>
                            <input type="text" name="nik" class="form-control bg-light" placeholder="Nomor Induk Kependudukan">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Pilih Rombel / Kelas</label>
                            <select name="rombel_id" class="form-select bg-light">
                                <option value="">-- Pilih Rombel --</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select bg-light" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control bg-light" placeholder="Kota kelahiran">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control bg-light">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" class="form-control bg-light" placeholder="Nama ayah/ibu/wali">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-secondary small">No. HP WhatsApp Orang Tua</label>
                            <input type="text" name="no_hp_ortu" class="form-control bg-light" placeholder="081234567890">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Alamat Tempat Tinggal</label>
                            <textarea name="alamat" rows="2" class="form-control bg-light" placeholder="Alamat lengkap..."></textarea>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <label class="form-label fw-semibold text-primary small">Buat Akun Siswa (Opsional untuk Login & Absen Mandiri)</label>
                            <div class="row g-2">
                                <div class="col-6"><input type="email" name="email_siswa" class="form-control bg-light" placeholder="email.siswa@sekolah.sch.id"></div>
                                <div class="col-6"><input type="password" name="password" class="form-control bg-light" placeholder="Kata Sandi Login"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                        <i class="bi bi-check-lg me-1"></i> Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
