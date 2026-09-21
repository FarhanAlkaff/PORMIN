@extends('layouts.admin')
@section('title', 'Aturan Usia '.$grade->code)
@section('content')
<a href="{{ route('admin.grades') }}" class="text-muted">← Grade</a>
<h1 class="section-title">Aturan Usia · {{ $grade->code }} — {{ $grade->name }}</h1>
<p class="text-muted">Usia dihitung pada tanggal acuan setiap tahun ajaran (default 1 Juli).</p>

<form method="POST" action="{{ route('admin.grades.age-rule.save', $grade) }}" class="p-3 bg-white rounded-4 shadow-sm" style="max-width:720px;">
    @csrf
    <h6 class="mb-2">Usia Minimum</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4"><label class="form-label">Tahun</label><input type="number" min="0" name="minimum_age_years" class="form-control" value="{{ $rule->minimum_age_years }}"></div>
        <div class="col-md-4"><label class="form-label">Bulan</label><input type="number" min="0" name="minimum_age_months" class="form-control" value="{{ $rule->minimum_age_months }}"></div>
        <div class="col-md-4"><label class="form-label">Hari</label><input type="number" min="0" name="minimum_age_days" class="form-control" value="{{ $rule->minimum_age_days }}"></div>
    </div>
    <h6 class="mb-2">Usia Maksimum</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4"><label class="form-label">Tahun</label><input type="number" min="0" name="maximum_age_years" class="form-control" value="{{ $rule->maximum_age_years }}"></div>
        <div class="col-md-4"><label class="form-label">Bulan</label><input type="number" min="0" name="maximum_age_months" class="form-control" value="{{ $rule->maximum_age_months }}"></div>
        <div class="col-md-4"><label class="form-label">Hari</label><input type="number" min="0" name="maximum_age_days" class="form-control" value="{{ $rule->maximum_age_days }}"></div>
    </div>
    <h6 class="mb-2">Tanggal Acuan</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-6"><label class="form-label">Bulan</label><input type="number" min="1" max="12" name="reference_month" class="form-control" value="{{ $rule->reference_month ?? 7 }}" required></div>
        <div class="col-md-6"><label class="form-label">Hari</label><input type="number" min="1" max="31" name="reference_day" class="form-control" value="{{ $rule->reference_day ?? 1 }}" required></div>
    </div>
    <button class="btn btn-azhar" data-testid="btn-save-age-rule">Simpan Aturan Usia</button>
</form>
@endsection
