@extends('layouts.app')

@section('title', 'Absen Mandiri Siswa - SekolahKu')
@section('page_title', 'Portal Absen Mandiri Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-custom p-4 p-md-5 text-center bg-white">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3 shadow-xs" style="width:76px; height:76px;">
                <i class="bi bi-person-check-fill fs-2"></i>
            </div>

            <h4 class="fw-bold mb-1 text-dark">{{ $siswa->nama_lengkap }}</h4>
            <p class="text-muted mb-3">{{ $siswa->rombel->nama_rombel ?? 'Rombel Belum Diatur' }} | NISN: {{ $siswa->nisn ?: '-' }}</p>

            <div class="p-3 bg-light-subtle rounded-3 mb-4 border">
                <div class="small text-uppercase text-muted fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">HARI & TANGGAL</div>
                <div class="fw-bold text-dark fs-5">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</div>
            </div>

            @if($presensiToday)
                <div class="p-3.5 bg-success-subtle text-success border border-success-subtle rounded-3 mb-3 text-start d-flex align-items-start gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-success">Presensi Anda Sudah Tercatat</h6>
                        <div class="small text-dark">
                            Jam Masuk: <strong>{{ $presensiToday->jam_masuk }} WIB</strong> | Status: <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2">{{ $presensiToday->status }}</span>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('presensi.mandiri.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-3 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-2" style="min-height: 52px;">
                        <i class="bi bi-hand-index-thumb fs-4"></i> KLIK UNTUK ABSEN SEKARANG
                    </button>
                </form>
                <small class="text-muted d-block mt-3">*Absen mandiri dilakukan setiap pagi sebelum jam pelajaran dimulai.</small>
            @endif
        </div>
    </div>
</div>
@endsection
