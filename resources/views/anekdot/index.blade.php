@extends('layouts.app')

@section('title', 'Catatan Anekdot Perkembangan - SekolahKu')
@section('page_title', 'Catatan Anekdot Siswa')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <div>
            <h5 class="fw-bold m-0 text-dark"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Catatan Peristiwa & Anekdot Perkembangan</h5>
            <small class="text-muted">Dokumentasi perilaku dan capaian tumbuh kembang anak didik</small>
        </div>
        @hasanyrole('Superadmin|School Admin|Guru')
            <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAnekdotModal" style="min-height: 40px;">
                <i class="bi bi-plus-lg me-1"></i> Catat Anekdot
            </button>
        @endhasanyrole
    </div>

    <!-- Timeline list -->
    <div class="row g-3">
        @forelse($anekdots as $a)
            <div class="col-12">
                <div class="p-3 p-sm-3.5 bg-light-subtle rounded-3 border">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-1.5 mb-2">
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ $a->siswa->nama_lengkap ?? '-' }}</h6>
                            <small class="text-muted">Tanggal: {{ $a->tanggal->translatedFormat('d F Y') }} | Pencatat: <strong>{{ $a->guru->nama_lengkap ?? 'Guru' }}</strong></small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1" style="font-size: 0.75rem;">Anekdot Perkembangan</span>
                    </div>

                    <div class="mb-2">
                        <strong class="text-dark small d-block mb-1">Deskripsi Peristiwa:</strong>
                        <p class="m-0 text-dark small" style="line-height: 1.5;">{{ $a->peristiwa }}</p>
                    </div>

                    @if($a->analisis_capaian)
                        <div class="mb-2 bg-white p-2.5 rounded-2 border">
                            <strong class="text-success small d-block mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Analisis Capaian Perkembangan:</strong>
                            <p class="m-0 text-dark small" style="line-height: 1.5;">{{ $a->analisis_capaian }}</p>
                        </div>
                    @endif

                    @if($a->lampirans->count() > 0)
                        <div class="d-flex flex-wrap gap-2 mt-2 pt-2 border-top">
                            @foreach($a->lampirans as $l)
                                <a href="{{ asset('storage/' . $l->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2 px-2.5 py-1" style="font-size:0.75rem; min-height: 32px;">
                                    <i class="bi bi-paperclip me-1"></i> Lihat Lampiran
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">Belum ada catatan peristiwa anekdot perkembangan siswa.</div>
        @endforelse
    </div>
    <div class="mt-3">
        {{ $anekdots->links() }}
    </div>
</div>

<!-- Modal New Anekdot -->
@hasanyrole('Superadmin|School Admin|Guru')
<div class="modal fade" id="newAnekdotModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Catatan Anekdot Perkembangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('anekdot.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-7">
                            <label class="form-label fw-semibold text-secondary small">Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" class="form-select bg-light" required style="min-height: 42px;">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->rombel->nama_rombel ?? 'Rombel N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold text-secondary small">Tanggal Kejadian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control bg-light" value="{{ date('Y-m-d') }}" required style="min-height: 42px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Uraian Peristiwa (Perilaku/Kejadian Khusus) <span class="text-danger">*</span></label>
                            <textarea name="peristiwa" class="form-control bg-light" rows="3" placeholder="Jelaskan peristiwa yang diamati secara objektif..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Analisis Capaian Perkembangan</label>
                            <textarea name="analisis_capaian" class="form-control bg-light" rows="2" placeholder="Capaian nilai agama, jati diri, literasi, atau STEAM yang teramati..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small">Lampiran Foto Dokumentasi (Opsional)</label>
                            <input type="file" name="lampirans[]" class="form-control bg-light" accept="image/*,application/pdf" multiple style="min-height: 42px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold" style="min-height: 40px;">Simpan Anekdot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endhasanyrole
@endsection
