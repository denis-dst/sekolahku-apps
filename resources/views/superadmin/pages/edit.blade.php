@extends('layouts.app')

@section('title', 'Edit Halaman CMS — ' . $page->title)
@section('page_title', 'Edit Materi Halaman: ' . $page->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold d-inline-flex align-items-center gap-1" style="min-height: 38px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Halaman
    </a>
</div>

<div class="card-custom p-3 p-sm-4 bg-white mb-4">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-12 col-md-8">
                <label class="form-label fw-semibold text-secondary small">Judul Utama Halaman <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control bg-light" value="{{ old('title', $page->title) }}" required style="min-height: 42px;">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold text-secondary small">Slug URL (Tetap)</label>
                <input type="text" class="form-control bg-light font-monospace" value="/{{ $page->slug }}" readonly style="min-height: 42px;">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold text-secondary small">Materi / Isi Konten Deskripsi <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control bg-light" rows="8" placeholder="Tuliskan penjelasan materi lengkap untuk halaman ini..." required>{{ old('content', $page->content) }}</textarea>
                <small class="text-muted" style="font-size:0.75rem;">Mendukung teks multi-baris / paragraf penjelasan profil perusahaan atau layanan support.</small>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold text-secondary small">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control bg-light" value="{{ old('meta_description', $page->meta_description) }}" placeholder="Ringkasan singkat untuk hasil pencarian Google..." style="min-height: 42px;">
            </div>

            @if($page->slug == 'hubungi-kami')
                <div class="col-12 mt-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-headset me-2 text-primary"></i>Pengaturan Khusus Kontak & Peta (Hubungi Kami)</h6>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold text-secondary small">Email Dukungan / Support</label>
                    <input type="email" name="contact_email" class="form-control bg-light" value="{{ old('contact_email', $page->contact_email) }}" placeholder="support@dndtech.id" style="min-height: 42px;">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold text-secondary small">Nomor WhatsApp Support (Format: 628xxx)</label>
                    <input type="text" name="contact_phone" class="form-control bg-light" value="{{ old('contact_phone', $page->contact_phone) }}" placeholder="6283878537818" style="min-height: 42px;">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold text-secondary small">Alamat Kantor Lengkap</label>
                    <textarea name="contact_address" class="form-control bg-light" rows="2" placeholder="Jl. Pendidikan Digital No. 88, Surabaya...">{{ old('contact_address', $page->contact_address) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold text-secondary small">Embed Peta Google Maps (Kode &lt;iframe&gt;)</label>
                    <textarea name="contact_maps_embed" class="form-control bg-light font-monospace text-xs" rows="3" placeholder="<iframe src='https://www.google.com/maps/embed...'></iframe>">{{ old('contact_maps_embed', $page->contact_maps_embed) }}</textarea>
                    <small class="text-muted" style="font-size:0.75rem;">Salin kode semat (embed iframe) langsung dari Google Maps.</small>
                </div>
            @endif

            <div class="col-12 mt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $page->is_active ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-dark small" for="activeSwitch">Status Halaman Aktif & Publik</label>
                </div>
            </div>
        </div>

        <div class="border-top pt-3 mt-4 text-end">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-light me-2 rounded-3 px-3" style="min-height: 42px;">Batal</a>
            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-xs" style="min-height: 42px;">
                <i class="bi bi-save me-1"></i> Simpan Perubahan Materi
            </button>
        </div>
    </form>
</div>
@endsection
