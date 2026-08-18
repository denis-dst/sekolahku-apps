@extends('layouts.app')

@section('title', 'Pengaturan Server WAHA (WhatsApp HTTP API) - SekolahKu')
@section('page_title', 'Pengaturan Server WAHA & WhatsApp Gateway')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <!-- Banner Info -->
        <div class="hero-banner mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%); border:1px solid #ccfbf1; border-radius:1.25rem; padding:1.75rem 2rem;">
            <div>
                <span class="text-xs fw-bold text-uppercase tracking-wider" style="color:#0f766e; letter-spacing:1.5px; font-size:0.75rem;">WHATSAPP GATEWAY ENGINE</span>
                <h4 class="fw-bold mt-1 mb-2" style="color:#0f172a; font-family:'Outfit';">WAHA (WhatsApp HTTP API) Server Config</h4>
                <p class="text-muted m-0 small">Kelola URL Server Docker, API Key, Nama Session, dan Nomor Pengirim Notifikasi WhatsApp Sekolah.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ rtrim($wahaUrl, '/') }}/dashboard" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka WAHA Dashboard
                </a>
                <a href="{{ rtrim($wahaUrl, '/') }}/dashboard/#/swagger" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-code-slash me-1"></i> Swagger Docs
                </a>
            </div>
        </div>

        <div class="card-custom p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h5 class="fw-bold m-0" style="color:#0f172a;"><i class="bi bi-whatsapp text-success me-2"></i>Parameter Koneksi WAHA API</h5>
                <form action="{{ route('admin.waha.test') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="waha_url" value="{{ $wahaUrl }}">
                    <input type="hidden" name="waha_api_key" value="{{ $wahaApiKey }}">
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                        <i class="bi bi-broadcast me-1"></i> Uji Koneksi WAHA Server
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.waha.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark">WAHA Base Server URL</label>
                        <input type="url" name="waha_url" class="form-control form-control-lg" value="{{ old('waha_url', $wahaUrl) }}" placeholder="http://localhost:3000" required>
                        <small class="text-muted">URL host server Docker WAHA Anda (misal: <code>http://localhost:3000</code> atau domain server Anda).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark">WAHA API Key (`X-Api-Key`)</label>
                        <input type="text" name="waha_api_key" class="form-control form-control-lg font-monospace text-xs" value="{{ old('waha_api_key', $wahaApiKey) }}" placeholder="ec5df26445cb42c7820dc611b3618c37">
                        <small class="text-muted">Kunci API unik yang diset pada Docker container WAHA (Header: <code>X-Api-Key</code>).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark">WAHA Session Name</label>
                        <input type="text" name="waha_session" class="form-control" value="{{ old('waha_session', $wahaSession) }}" placeholder="default" required>
                        <small class="text-muted">Nama sesi WhatsApp yang aktif di WAHA (default: <code>default</code>).</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-dark">Nomor Pengirim Default (Format: 628xxx)</label>
                        <input type="text" name="waha_sender_phone" class="form-control" value="{{ old('waha_sender_phone', $wahaSenderPhone) }}" placeholder="6283878537818" required>
                        <small class="text-muted">Nomor WhatsApp utama pengirim notifikasi presensi & pembayaran SPP.</small>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle-fill text-primary me-2"></i>Status Kredensial WAHA Docker Terpasang:</div>
                    <div class="row g-2 text-xs font-monospace">
                        <div class="col-12 col-md-6">WAHA_API_KEY: <strong class="text-success">{{ Str::mask($wahaApiKey, '*', 4, -4) }}</strong></div>
                        <div class="col-12 col-md-6">SENDER_PHONE: <strong class="text-primary">+{{ $wahaSenderPhone }}</strong></div>
                    </div>
                </div>

                <div class="text-end border-top pt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Konfigurasi WAHA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
