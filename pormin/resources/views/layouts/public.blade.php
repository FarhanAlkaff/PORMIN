@extends('layouts.base')

@section('body')
<nav class="navbar navbar-expand-lg navbar-azhar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
            <span class="brand-mark">AZ</span>
            <div class="d-flex flex-column lh-1">
                <span style="font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;">PORMIN</span>
                <small style="opacity:.85;font-size:.72rem;">Al-Azhar Cairo Palembang</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" data-testid="navbar-toggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}" data-testid="nav-home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('info.pendaftaran') }}" data-testid="nav-info">Informasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('info.persyaratan') }}" data-testid="nav-persyaratan">Persyaratan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('info.alur') }}" data-testid="nav-alur">Alur</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('info.jadwal') }}" data-testid="nav-jadwal">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('info.faq') }}" data-testid="nav-faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('registration.check') }}" data-testid="nav-cek">Cek Pendaftaran</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.login') }}" data-testid="nav-admin">Login Admin</a></li>
                <li class="nav-item ms-lg-2"><a class="btn btn-gold px-3" href="{{ route('registration.create') }}" data-testid="nav-daftar-cta">Daftar Sekarang</a></li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer class="footer mt-5 py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="brand-mark">AZ</span>
                <div>
                    <div style="font-family:'Fraunces',serif;font-weight:700;">PORMIN</div>
                    <small style="opacity:.75;">Portal Minat · Al-Azhar Cairo Palembang</small>
                </div>
            </div>
            <small>© {{ date('Y') }} Al-Azhar Cairo Palembang. Hak cipta dilindungi.</small>
        </div>
        <div class="small">
            <div><i class="bi bi-geo-alt me-1"></i>{{ \App\Models\InformationSetting::get('contact_address') }}</div>
            <div><i class="bi bi-telephone me-1"></i>{{ \App\Models\InformationSetting::get('contact_phone') }}</div>
            <div><i class="bi bi-envelope me-1"></i>{{ \App\Models\InformationSetting::get('contact_email') }}</div>
        </div>
    </div>
</footer>
@endsection
