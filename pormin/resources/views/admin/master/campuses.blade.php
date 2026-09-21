@extends('layouts.admin')
@section('title', 'Kampus')
@section('content')
<h1 class="section-title mb-3">Kampus</h1>
<div class="row g-3">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-4 shadow-sm">
            <h6>Tambah / Ubah</h6>
            <form method="POST" action="{{ route('admin.campuses.save') }}">@csrf
                <input type="hidden" name="id" id="c_id">
                <div class="mb-2"><label class="form-label">Nama</label><input name="name" id="c_name" class="form-control" required></div>
                <div class="mb-2"><label class="form-label">Alamat</label><input name="address" id="c_addr" class="form-control" required></div>
                <div class="form-check"><input type="checkbox" name="is_active" id="c_act" value="1" class="form-check-input" checked><label class="form-check-label" for="c_act">Aktif</label></div>
                <button class="btn btn-azhar mt-2 w-100">Simpan</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="p-2 bg-white rounded-4 shadow-sm">
            <table class="table table-vcenter mb-0"><thead><tr><th>Nama</th><th>Alamat</th><th>Aktif</th><th></th></tr></thead>
            <tbody>@foreach($items as $i)<tr><td><b>{{ $i->name }}</b></td><td>{{ $i->address }}</td><td>@if($i->is_active)<span class="status-pill status-diterima">Aktif</span>@else-@endif</td><td class="text-end"><button class="btn btn-sm btn-outline-primary" onclick="edit({{ $i->id }},'{{ addslashes($i->name) }}','{{ addslashes($i->address) }}',{{ $i->is_active?1:0 }})"><i class="bi bi-pencil"></i></button></td></tr>@endforeach</tbody></table>
        </div>
    </div>
</div>
<script>function edit(id,n,a,act){document.getElementById('c_id').value=id;document.getElementById('c_name').value=n;document.getElementById('c_addr').value=a;document.getElementById('c_act').checked=!!act;}</script>
@endsection
