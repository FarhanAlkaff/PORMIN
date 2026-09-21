@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
@php
    $anActive = \App\Models\InformationSetting::get('announcement_active') === '1';
    $anTitle = \App\Models\InformationSetting::get('announcement_title', 'Pengumuman');
    $anItems = collect(explode("\n", (string)\App\Models\InformationSetting::get('announcement_items', '')))->map(fn($x)=>trim($x))->filter()->values();
@endphp
@if($anActive && $anItems->count())
<div class="announcement-strip py-2 px-3">
    <div class="container d-flex align-items-center gap-3">
        <span class="badge rounded-pill fw-bold" style="background:var(--azhar-gold);color:var(--azhar-green-dark);letter-spacing:.08em;font-size:.72rem;padding:.5rem .8rem;"><i class="bi bi-megaphone-fill me-1"></i>{{ $anTitle }}</span>
        <div class="marquee flex-grow-1" data-testid="announcement-marquee">
            <div class="marquee-track">
                @foreach($anItems as $item)<span class="marquee-item">{{ $item }}</span><span class="marquee-sep">◆</span>@endforeach
                @foreach($anItems as $item)<span class="marquee-item">{{ $item }}</span><span class="marquee-sep">◆</span>@endforeach
            </div>
        </div>
    </div>
</div>
@push('head')
<style>
    .announcement-strip{background:linear-gradient(90deg,var(--azhar-green-dark),var(--azhar-green));color:#fff;border-bottom:2px solid var(--azhar-gold);}
    .marquee{overflow:hidden;white-space:nowrap;position:relative;mask-image:linear-gradient(to right,transparent,#000 5%,#000 95%,transparent);}
    .marquee-track{display:inline-flex;gap:1.5rem;animation:pormin-marquee 32s linear infinite;padding-left:100%;}
    .marquee-item{font-weight:600;font-size:.92rem;}
    .marquee-sep{color:var(--azhar-gold);opacity:.7;}
    @keyframes pormin-marquee{from{transform:translateX(0);}to{transform:translateX(-50%);}}
</style>
@endpush
@endif
<section class="hero py-5">
    <div class="container hero-inner py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge rounded-pill" style="background:rgba(201,162,75,.22);color:var(--azhar-gold);letter-spacing:.15em;">PORTAL MINAT · PMB {{ optional($activeYear)->name }}</span>
                <h1 class="hero-title mt-3 mb-2" style="font-size:clamp(2.25rem,5vw,3.75rem);">{{ $settings['landing_hero_title'] ?? 'Pendaftaran Murid Baru' }}</h1>
                <h2 class="h4 mb-3" style="color:var(--azhar-gold);font-family:'Fraunces',serif;">{{ $settings['landing_hero_subtitle'] ?? 'Al-Azhar Cairo Palembang' }}</h2>
                <p class="lead mb-4" style="max-width:640px;opacity:.9;">{{ $settings['landing_hero_description'] }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('registration.create') }}" class="btn btn-gold btn-lg px-4" data-testid="hero-daftar-btn">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
                    <a href="{{ route('registration.check') }}" class="btn btn-outline-light btn-lg px-4" data-testid="hero-cek-btn">Cek Pendaftaran</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="p-4 rounded-4" style="background:rgba(255,255,255,.08);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.14);">
                    <h5 class="mb-3" style="font-family:'Fraunces',serif;">Ringkas & Aman</h5>
                    <div class="d-flex align-items-start gap-3 mb-3"><i class="bi bi-shield-check fs-4" style="color:var(--azhar-gold);"></i><div><strong>Validasi Usia Otomatis</strong><br><small style="opacity:.85;">Berdasarkan 1 Juli tahun ajaran.</small></div></div>
                    <div class="d-flex align-items-start gap-3 mb-3"><i class="bi bi-fingerprint fs-4" style="color:var(--azhar-gold);"></i><div><strong>Bebas Duplikat</strong><br><small style="opacity:.85;">Deteksi cerdas nama + tanggal lahir.</small></div></div>
                    <div class="d-flex align-items-start gap-3"><i class="bi bi-qr-code fs-4" style="color:var(--azhar-gold);"></i><div><strong>Bukti PDF + QR</strong><br><small style="opacity:.85;">Unduh & verifikasi kapan saja.</small></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <span class="text-uppercase small" style="letter-spacing:.15em;color:var(--azhar-gold);">Jenjang</span>
                <h2 class="section-title mb-0">Pilih Jenjang Pendaftaran</h2>
            </div>
            <small class="text-muted">Aturan usia dihitung pada 1 Juli tahun ajaran</small>
        </div>
        <div class="row g-3">
            @foreach($grades as $g)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('registration.create', ['grade' => $g->id, 'year' => optional($activeYear)->id]) }}" class="text-decoration-none" data-testid="grade-card-{{ strtolower($g->code) }}">
                        <div class="grade-card p-3 text-center">
                            <div class="grade-badge mx-auto mb-2">{{ $g->code }}</div>
                            <div class="fw-bold" style="color:var(--azhar-green-dark);">{{ $g->name }}</div>
                            <small class="text-muted d-block mt-1">{{ Str::limit($g->description, 60) }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4"><h4 class="section-title"><i class="bi bi-clipboard-check me-2" style="color:var(--azhar-gold);"></i>Persyaratan</h4><p class="text-muted" style="white-space:pre-line;">{{ Str::limit($settings['info_persyaratan'] ?? '', 260) }}</p><a href="{{ route('info.persyaratan') }}">Selengkapnya →</a></div>
            <div class="col-md-4"><h4 class="section-title"><i class="bi bi-diagram-3 me-2" style="color:var(--azhar-gold);"></i>Alur Pendaftaran</h4><p class="text-muted" style="white-space:pre-line;">{{ Str::limit($settings['info_alur'] ?? '', 260) }}</p><a href="{{ route('info.alur') }}">Selengkapnya →</a></div>
            <div class="col-md-4"><h4 class="section-title"><i class="bi bi-calendar-event me-2" style="color:var(--azhar-gold);"></i>Jadwal</h4><p class="text-muted" style="white-space:pre-line;">{{ Str::limit($settings['info_jadwal'] ?? '', 260) }}</p><a href="{{ route('info.jadwal') }}">Selengkapnya →</a></div>
        </div>
    </div>
</section>
@endsection
