@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="section-title mb-0">Dashboard</h1><small class="text-muted">Ringkasan pendaftaran PORMIN</small></div>
    <a href="{{ route('admin.registrations.create') }}" class="btn btn-azhar" data-testid="btn-input-lama"><i class="bi bi-plus-circle me-1"></i>Input Data Lama</a>
</div>

<div class="row g-3 mb-4">
    @php $kpi=[['Total Pendaftar','total','people','#0f5f3a'],['Menunggu Validasi','menunggu','hourglass','#8a6100'],['Lolos Administrasi','lolos_admin','check2-circle','#0b4c9a'],['Layak Observasi','layak','clipboard-check','#0a6b2e'],['Dipanggil Observasi','dipanggil','megaphone','#5a1aa8'],['Diterima','diterima','patch-check','#0f5f3a']]; @endphp
    @foreach($kpi as $k)
        <div class="col-6 col-md-4 col-lg-2">
            <div class="kpi-card h-100"><i class="bi bi-{{ $k[2] }} fs-4" style="color:{{ $k[3] }};"></i><div class="text-muted small mt-1">{{ $k[0] }}</div><div class="kpi-num" data-testid="kpi-{{ $k[1] }}">{{ $counts[$k[1]] }}</div></div>
        </div>
    @endforeach
</div>

<div class="p-3 bg-white rounded-4 shadow-sm">
    <h5 class="section-title mb-3">Statistik per Grade</h5>
    <div class="table-responsive"><table class="table table-vcenter">
        <thead><tr><th>Grade</th><th class="text-end">Pendaftar</th><th class="text-end">Lolos Admin</th><th class="text-end">Layak Observasi</th><th class="text-end">Diterima</th></tr></thead>
        <tbody>@foreach($perGrade as $g)<tr><td><b>{{ $g->code }}</b> · {{ $g->name }}</td><td class="text-end">{{ $g->total }}</td><td class="text-end">{{ $g->lolos_admin }}</td><td class="text-end">{{ $g->layak }}</td><td class="text-end">{{ $g->diterima }}</td></tr>@endforeach</tbody>
    </table></div>
</div>
@endsection
