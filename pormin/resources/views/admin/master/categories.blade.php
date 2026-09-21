@extends('layouts.admin')
@section('title', 'Kategori Siswa')
@section('content')
<h1 class="section-title mb-3">Kategori Siswa</h1>
<div class="row g-3">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-4 shadow-sm">
            <h6>Tambah / Ubah</h6>
            <form method="POST" action="{{ route('admin.categories.save') }}">@csrf
                <input type="hidden" name="id" id="k_id">
                <div class="mb-2"><label class="form-label">Nama</label><input name="name" id="k_name" class="form-control" required></div>
                <div class="form-check"><input type="checkbox" name="is_active" id="k_act" value="1" class="form-check-input" checked><label for="k_act" class="form-check-label">Aktif</label></div>
                <button class="btn btn-azhar mt-2 w-100">Simpan</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="p-2 bg-white rounded-4 shadow-sm">
            <table class="table table-vcenter mb-0"><thead><tr><th>Nama</th><th>Aktif</th><th></th></tr></thead>
            <tbody>@foreach($items as $i)<tr><td>{{ $i->name }}</td><td>@if($i->is_active)<span class="status-pill status-diterima">Aktif</span>@else-@endif</td><td class="text-end"><button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('k_id').value={{ $i->id }};document.getElementById('k_name').value='{{ addslashes($i->name) }}';document.getElementById('k_act').checked={{ $i->is_active?1:0 }};"><i class="bi bi-pencil"></i></button></td></tr>@endforeach</tbody></table>
        </div>
    </div>
</div>
@endsection
