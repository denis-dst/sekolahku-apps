@extends('layouts.app')

@section('title', 'Manajemen Halaman Publik - SekolahKu')
@section('page_title', 'Halaman Publik CMS')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark m-0">Daftar Halaman Publik Informatif</h5>
            <p class="text-muted small m-0">Atur judul, materi deskripsi, email, telepon, alamat, dan embed peta untuk halaman publik landing page.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Judul Halaman</th>
                    <th>Slug URL</th>
                    <th>Detail Kontak Terkait</th>
                    <th>Status Halaman</th>
                    <th>Terakhir Diperbarui</th>
                    <th class="text-center">Aksi Superadmin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $idx => $p)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->title }}</div>
                            <small class="text-muted">{{ Str::limit(strip_tags($p->content), 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace">/{{ $p->slug }}</span>
                        </td>
                        <td>
                            @if($p->contact_email || $p->contact_phone)
                                <div class="small fw-semibold text-dark"><i class="bi bi-envelope me-1 text-primary"></i> {{ $p->contact_email ?? '-' }}</div>
                                <div class="small text-muted"><i class="bi bi-whatsapp me-1 text-success"></i> {{ $p->contact_phone ?? '-' }}</div>
                            @else
                                <span class="text-muted small">Materi Informasi Profil</span>
                            @endif
                        </td>
                        <td>
                            @if($p->is_active)
                                <span class="badge bg-success-subtle text-success border"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-eye-slash me-1"></i> Sembunyi</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $p->updated_at ? $p->updated_at->format('d/m/Y H:i') : '-' }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ url($p->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3" title="Lihat Halaman Publik">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Lihat
                                </a>
                                <a href="{{ route('admin.pages.edit', $p->id) }}" class="btn btn-sm btn-primary rounded-3 fw-bold">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Materi & Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada halaman publik yang diatur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
