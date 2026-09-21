@extends('layouts.admin')
@section('title', 'Grade & Aturan Usia')
@section('content')
<h1 class="section-title mb-3">Grade & Aturan Usia</h1>
<div class="p-2 bg-white rounded-4 shadow-sm">
    <table class="table table-vcenter mb-0">
        <thead><tr><th>Kode</th><th>Nama</th><th>Aturan Usia</th><th>Aktif</th><th></th></tr></thead>
        <tbody>
            @foreach($items as $g)
            <tr>
                <td><b>{{ $g->code }}</b></td>
                <td>{{ $g->name }}<br><small class="text-muted">{{ $g->description }}</small></td>
                <td><small>
                    @if($g->ageRule)
                        Min: {{ $g->ageRule->minimum_age_years ?? 0 }}th {{ $g->ageRule->minimum_age_months ?? 0 }}bl · Max: {{ $g->ageRule->maximum_age_years ?? '-' }}th {{ $g->ageRule->maximum_age_months ?? 0 }}bl<br>
                        Acuan: {{ str_pad($g->ageRule->reference_day,2,'0',STR_PAD_LEFT) }}/{{ str_pad($g->ageRule->reference_month,2,'0',STR_PAD_LEFT) }}
                    @else <em>belum diatur</em> @endif
                </small></td>
                <td>@if($g->is_active)<span class="status-pill status-diterima">Aktif</span>@else-@endif</td>
                <td class="text-end"><a href="{{ route('admin.grades.age-rule', $g) }}" class="btn btn-sm btn-outline-primary" data-testid="btn-edit-rule-{{ $g->code }}"><i class="bi bi-sliders me-1"></i>Aturan Usia</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
