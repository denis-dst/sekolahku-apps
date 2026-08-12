@extends('layouts.app')

@section('title', 'Absen Mandiri Siswa - SekolahKu')
@section('page_title', 'Portal Absen Mandiri Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-custom p-4 p-md-5 text-center">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3 shadow-sm" style="width:90px; height:90px;">
                <i class="bi bi-person-check-fill fs-1"></i>
            </div>

            <h3 class="fw-bold mb-1">{{ $siswa->nama_lengkap }}</h3>
            <p class="text-muted mb-3">{{ $siswa->rombel->nama_rombel ?? 'Rombel Belum Diatur' }} | NISN: {{ $siswa->nisn ?: '-' }}</p>

            <div class="p-3 bg-light rounded-3 mb-4 border">
                <div class="small text-uppercase text-muted fw-bold mb-1">HARI & TANGGAL</div>
                <div class="fw-extrabold text-dark fs-5">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</div>
            </div>

            @if($presensiToday)
                <div class="alert alert-success py-3 rounded-3 shadow-sm border-0 mb-3">
                    <i class="bi bi-check-circle-fill fs-4 d-block mb-1"></i>
                    <h5 class="fw-bold mb-1">Anda Sudah Absen Hari Ini</h5>
                    <div class="small">Jam Masuk: <strong>{{ $presensiToday->jam_masuk }} WIB</strong> (Status: <span class="badge bg-success">{{ $presensiToday->status }}</span>)</div>
                </div>
            @else
                <form action="{{ route('presensi.mandiri.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-3 fw-bold shadow">
                        <i class="bi bi-hand-index-thumb me-2 fs-4 align-middle"></i> KLIK UNTUK ABSEN SEKARANG
                    </button>
                </form>
                <small class="text-muted d-block mt-3">*Absen mandiri dilakukan setiap pagi sebelum jam pelajaran dimulai.</small>
            @endif
        </div>
    </div>
</div>
@endsection
