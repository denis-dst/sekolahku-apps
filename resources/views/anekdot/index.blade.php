@extends('layouts.app')

@section('title', 'Catatan Anekdot Perkembangan - SekolahKu')
@section('page_title', 'Catatan Anekdot Perkembangan Siswa')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Catatan Peristiwa & Anekdot Perkembangan</h5>
        @hasanyrole('Superadmin|School Admin|Guru')
            <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newAnekdotModal">
                <i class="bi bi-plus-lg me-1"></i> Catat Peristiwa Anekdot
            </button>
        @endhasanyrole
    </div>

    <!-- Timeline list -->
    <div class="row g-3">
        @forelse($anekdots as $a)
            <div class="col-12">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ $a->siswa->nama_lengkap }}</h6>
                            <small class="text-muted">Tanggal: {{ $a->tanggal->format('d F Y') }} | Pencatat: {{ $a->guru->nama_lengkap ?? 'Guru' }}</small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border">Anekdot Perkembangan</span>
                    </div>

                    <div class="mb-2">
                        <strong class="text-dark small d-block">Deskripsi Peristiwa:</strong>
                        <p class="m-0 text-slate-700 small">{{ $a->peristiwa }}</p>
                    </div>

                    @if($a->analisis_capaian)
                        <div class="mb-2 bg-white p-2 rounded border">
                            <strong class="text-success small d-block"><i class="bi bi-graph-up-arrow me-1"></i>Analisis Capaian Perkembangan:</strong>
                            <p class="m-0 text-slate-700 small">{{ $a->analisis_capaian }}</p>
                        </div>
                    @endif

                    @if($a->lampirans->count() > 0)
                        <div class="d-flex gap-2 mt-2">
                            @foreach($a->lampirans as $l)
                                <a href="{{ asset('storage/' . $l->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2" style="font-size:0.75rem;">
                                    <i class="bi bi-paperclip"></i> Lihat Lampiran
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">Belum ada catatan peristiwa anekdot perkembangan siswa.</div>
        @endforelse
    </div>
    <div class="mt-3">
        {{ $anekdots->links() }}
    </div>
</div>

<!-- Modal New Anekdot -->
@hasanyrole('Superadmin|School Admin|Guru')
<div class="modal fade" id="newAnekdotModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Catatan Anekdot Perkembangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('anekdot.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            @foreach($siswas as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->rombel->nama_rombel ?? 'Rombel N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kejadian</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Uraian Peristiwa (Perilaku/Kejadian Khusus)</label>
                        <textarea name="peristiwa" class="form-control" rows="3" placeholder="Jelaskan peristiwa yang diamati secara objektif..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Analisis Capaian Perkembangan</label>
                        <textarea name="analisis_capaian" class="form-control" rows="2" placeholder="Capaian nilai agama, jati diri, literasi, atau STEAM yang teramati..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lampiran Foto Dokumentasi (Opsional)</label>
                        <input type="file" name="lampirans[]" class="form-control" accept="image/*,application/pdf" multiple>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Anekdot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endhasanyrole
@endsection
