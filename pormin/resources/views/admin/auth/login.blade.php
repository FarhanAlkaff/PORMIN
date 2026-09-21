@extends('layouts.base')
@section('title', 'Login Admin')
@section('body')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,var(--azhar-green-dark),var(--azhar-green));">
    <div class="card border-0 shadow-lg" style="width:100%;max-width:420px;border-radius:18px;">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="brand-mark d-inline-flex mb-2" style="width:56px;height:56px;font-size:1.6rem;">AZ</span>
                <h1 class="section-title mb-0">PORMIN Admin</h1>
                <small class="text-muted">Al-Azhar Cairo Palembang</small>
            </div>
            @if($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}" data-testid="admin-login-email"></div>
                <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required data-testid="admin-login-password"></div>
                <div class="form-check mb-3"><input type="checkbox" name="remember" class="form-check-input" id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div>
                <button class="btn btn-azhar w-100" data-testid="admin-login-submit">Masuk</button>
            </form>
            <div class="text-center mt-3"><a href="{{ route('landing') }}" class="text-muted small">← Kembali ke beranda</a></div>
        </div>
    </div>
</div>
@endsection
