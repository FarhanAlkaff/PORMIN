@extends('layouts.base')

@section('body')
<div class="d-flex">
    <aside class="sidebar p-3" style="width:260px;">
        <a href="{{ route('landing') }}" class="d-flex align-items-center gap-2 mb-4 text-white text-decoration-none">
            <span class="brand-mark">AZ</span>
            <div>
                <div style="font-family:'Fraunces',serif;font-weight:700;">PORMIN Admin</div>
                <small style="opacity:.75;">Al-Azhar Cairo Palembang</small>
            </div>
        </a>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-testid="sidebar-dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="{{ route('admin.registrations.index') }}" class="{{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}" data-testid="sidebar-registrations"><i class="bi bi-file-earmark-person me-2"></i>Data Pendaftaran</a>
            <a href="{{ route('admin.registrations.create') }}" data-testid="sidebar-input-lama"><i class="bi bi-plus-square me-2"></i>Input Data Lama</a>
            <div class="text-uppercase small mt-3 mb-1" style="opacity:.65;letter-spacing:.05em;">Master Data</div>
            <a href="{{ route('admin.years') }}" class="{{ request()->routeIs('admin.years') ? 'active' : '' }}" data-testid="sidebar-years"><i class="bi bi-calendar-week me-2"></i>Tahun Ajaran</a>
            <a href="{{ route('admin.grades') }}" class="{{ request()->routeIs('admin.grades*') ? 'active' : '' }}" data-testid="sidebar-grades"><i class="bi bi-mortarboard me-2"></i>Grade & Aturan Usia</a>
            <a href="{{ route('admin.campuses') }}" class="{{ request()->routeIs('admin.campuses') ? 'active' : '' }}" data-testid="sidebar-campuses"><i class="bi bi-building me-2"></i>Kampus</a>
            <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}" data-testid="sidebar-categories"><i class="bi bi-tags me-2"></i>Kategori Siswa</a>
            <a href="{{ route('admin.information') }}" class="{{ request()->routeIs('admin.information') ? 'active' : '' }}" data-testid="sidebar-info"><i class="bi bi-info-square me-2"></i>Informasi Landing</a>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <button class="w-100 text-start border-0 bg-transparent" style="color:#e9e6d6;padding:.7rem 1rem;border-radius:8px;" data-testid="sidebar-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
        </nav>
    </aside>
    <main class="flex-grow-1 p-4" style="background:#f5f1e3;min-height:100vh;">
        @if(session('success'))<div class="alert alert-success" data-testid="flash-success">{{ session('success') }}</div>@endif
        @if(session('info'))<div class="alert alert-info" data-testid="flash-info">{{ session('info') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
</div>
@endsection
