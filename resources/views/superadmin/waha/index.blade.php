@extends('layouts.app')

@section('title', 'Pengaturan Server WAHA - SekolahKu')
@section('page_title', 'Server WAHA & WhatsApp')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <!-- Banner Info -->
        <div class="hero-banner mb-4 d-flex align-items-start align-items-sm-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-2 fw-semibold mb-1" style="font-size:0.75rem;">WHATSAPP GATEWAY ENGINE</span>
                <h4 class="fw-bold mt-1 mb-1 text-dark">WAHA (WhatsApp HTTP API) Server Config</h4>
                <p class="text-muted m-0 small">Kelola URL Server Docker, API Key, Nama Session, dan Nomor Pengirim Notifikasi WhatsApp Sekolah.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
                <a href="{{ rtrim($wahaUrl, '/') }}/dashboard" target="_blank" class="btn btn-outline-primary btn-sm rounded-3 px-3 fw-semibold flex-fill flex-sm-grow-0 d-flex align-items-center justify-content-center gap-1" style="min-height: 38px;">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Dashboard WAHA
                </a>
                <a href="{{ rtrim($wahaUrl, '/') }}/dashboard/#/swagger" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold flex-fill flex-sm-grow-0 d-flex align-items-center justify-content-center gap-1" style="min-height: 38px;">
                    <i class="bi bi-code-slash me-1"></i> Swagger Docs
                </a>
            </div>
        </div>

        <div class="card-custom p-3 p-sm-4 p-md-5 bg-white">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4 pb-3 border-bottom">
                <h5 class="fw-bold m-0 text-dark"><i class="bi bi-whatsapp text-success me-2"></i>Parameter Koneksi WAHA API</h5>
                <form action="{{ route('admin.waha.test') }}" method="POST" class="w-100 w-sm-auto">
                    @csrf
                    <input type="hidden" name="waha_url" value="{{ $wahaUrl }}">
                    <input type="hidden" name="waha_api_key" value="{{ $wahaApiKey }}">
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-3 fw-bold shadow-xs w-100 w-sm-auto" style="min-height: 38px;">
                        <i class="bi bi-broadcast me-1"></i> Uji Koneksi WAHA
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.waha.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">WAHA Base Server URL <span class="text-danger">*</span></label>
                        <input type="url" name="waha_url" class="form-control bg-light" value="{{ old('waha_url', $wahaUrl) }}" placeholder="http://localhost:3000" required style="min-height: 42px;">
                        <small class="text-muted" style="font-size: 0.75rem;">URL host server Docker WAHA Anda (misal: <code>http://localhost:3000</code>).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">WAHA API Key (`X-Api-Key`)</label>
                        <input type="text" name="waha_api_key" class="form-control bg-light font-monospace text-xs" value="{{ old('waha_api_key', $wahaApiKey) }}" placeholder="ec5df26445cb42c7820dc611b3618c37" style="min-height: 42px;">
                        <small class="text-muted" style="font-size: 0.75rem;">Kunci API unik pada Docker container WAHA (Header: <code>X-Api-Key</code>).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">WAHA Session Name <span class="text-danger">*</span></label>
                        <input type="text" name="waha_session" class="form-control bg-light" value="{{ old('waha_session', $wahaSession) }}" placeholder="default" required style="min-height: 42px;">
                        <small class="text-muted" style="font-size: 0.75rem;">Nama sesi WhatsApp yang aktif di WAHA (default: <code>default</code>).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Nomor Pengirim Default (Format: 628xxx) <span class="text-danger">*</span></label>
                        <input type="text" name="waha_sender_phone" class="form-control bg-light" value="{{ old('waha_sender_phone', $wahaSenderPhone) }}" placeholder="6283878537818" required style="min-height: 42px;">
                        <small class="text-muted" style="font-size: 0.75rem;">Nomor WhatsApp utama pengirim notifikasi presensi & SPP.</small>
                    </div>
                </div>

                <div class="p-3 bg-light-subtle rounded-3 border mb-4">
                    <div class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle-fill text-primary me-2"></i>Status Kredensial WAHA Docker Terpasang:</div>
                    <div class="row g-2 text-xs font-monospace">
                        <div class="col-12 col-md-6">WAHA_API_KEY: <strong class="text-success">{{ Str::mask($wahaApiKey, '*', 4, -4) }}</strong></div>
                        <div class="col-12 col-md-6">SENDER_PHONE: <strong class="text-primary">+{{ $wahaSenderPhone }}</strong></div>
                    </div>
                </div>

                <div class="text-end border-top pt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-xs w-100 w-sm-auto" style="min-height: 44px;">
                        <i class="bi bi-save me-1"></i> Simpan Konfigurasi WAHA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
