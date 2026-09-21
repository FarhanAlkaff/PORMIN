@extends('layouts.admin')
@section('title', 'Tahun Ajaran')
@section('content')
<h1 class="section-title mb-3">Tahun Ajaran</h1>
<div class="row g-3">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-4 shadow-sm">
            <h6>Tambah / Ubah</h6>
            <form method="POST" action="{{ route('admin.years.save') }}">@csrf
                <input type="hidden" name="id" id="y_id">
                <div class="mb-2"><label class="form-label">Nama (2026/2027)</label><input name="name" id="y_name" class="form-control" required></div>
                <div class="row"><div class="col-6"><label class="form-label">Tahun Awal</label><input type="number" name="start_year" id="y_start" class="form-control" required></div>
                <div class="col-6"><label class="form-label">Tahun Akhir</label><input type="number" name="end_year" id="y_end" class="form-control" required></div></div>
                <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_active" id="y_act" value="1"><label class="form-check-label" for="y_act">Aktifkan (nonaktifkan lainnya)</label></div>
                <button class="btn btn-azhar mt-2 w-100">Simpan</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="p-2 bg-white rounded-4 shadow-sm">
            <table class="table table-vcenter mb-0">
                <thead><tr><th>Nama</th><th>Start</th><th>End</th><th>Aktif</th><th></th></tr></thead>
                <tbody>@foreach($items as $it)<tr><td><b>{{ $it->name }}</b></td><td>{{ $it->start_year }}</td><td>{{ $it->end_year }}</td><td>@if($it->is_active)<span class="status-pill status-diterima">Aktif</span>@else-@endif</td>
                <td class="text-end"><button class="btn btn-sm btn-outline-primary" onclick="edit({{ $it->id }},'{{ $it->name }}',{{ $it->start_year }},{{ $it->end_year }},{{ $it->is_active?1:0 }})"><i class="bi bi-pencil"></i></button></td></tr>@endforeach</tbody>
            </table>
        </div>
    </div>
</div>
<script>
function edit(id,name,s,e,act){ document.getElementById('y_id').value=id; document.getElementById('y_name').value=name; document.getElementById('y_start').value=s; document.getElementById('y_end').value=e; document.getElementById('y_act').checked=!!act; window.scrollTo({top:0}); }
</script>
@endsection
