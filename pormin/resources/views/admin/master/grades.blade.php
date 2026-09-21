@extends('layouts.admin')
@section('title', 'Grade & Aturan Usia')
@section('content')
<h1 class="section-title mb-3">Grade & Aturan Usia</h1>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="p-3 bg-white rounded-4 shadow-sm">
            <h6 class="section-title mb-3"><i class="bi bi-plus-square me-1" style="color:var(--azhar-gold);"></i>Tambah / Ubah Grade</h6>
            <form method="POST" action="{{ route('admin.grades.save') }}">@csrf
                <input type="hidden" name="id" id="g_id">
                <div class="mb-2"><label class="form-label">Kode</label><input name="code" id="g_code" class="form-control" required data-testid="input-grade-code" placeholder="Mis: SMA"></div>
                <div class="mb-2"><label class="form-label">Nama</label><input name="name" id="g_name" class="form-control" required data-testid="input-grade-name"></div>
                <div class="mb-2"><label class="form-label">Deskripsi</label><textarea name="description" id="g_desc" class="form-control" rows="2"></textarea></div>
                <div class="mb-2"><label class="form-label">Urutan</label><input type="number" name="sort_order" id="g_sort" class="form-control" value="0"></div>
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" id="g_act" value="1" checked data-testid="input-grade-active"><label class="form-check-label" for="g_act">Aktifkan (muncul di halaman pendaftaran)</label></div>
                <button class="btn btn-azhar w-100" data-testid="btn-save-grade">Simpan Grade</button>
                <button type="button" class="btn btn-outline-secondary w-100 mt-2" onclick="resetForm()">Reset Form</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="p-2 bg-white rounded-4 shadow-sm">
            <table class="table table-vcenter mb-0">
                <thead><tr><th>Kode</th><th>Nama</th><th>Aturan Usia</th><th class="text-center">Status</th><th></th></tr></thead>
                <tbody>
                    @foreach($items as $g)
                    <tr>
                        <td><b>{{ $g->code }}</b></td>
                        <td>{{ $g->name }}<br><small class="text-muted">{{ Str::limit($g->description, 60) }}</small></td>
                        <td><small>
                            @if($g->ageRule)
                                Min: {{ $g->ageRule->minimum_age_years ?? 0 }}th {{ $g->ageRule->minimum_age_months ?? 0 }}bl<br>
                                Max: {{ $g->ageRule->maximum_age_years ?? '-' }}th {{ $g->ageRule->maximum_age_months ?? 0 }}bl<br>
                                <span class="text-muted">Acuan: {{ str_pad($g->ageRule->reference_day,2,'0',STR_PAD_LEFT) }}/{{ str_pad($g->ageRule->reference_month,2,'0',STR_PAD_LEFT) }}</span>
                            @else <em class="text-muted">Belum diatur</em> @endif
                        </small></td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.grades.save') }}" class="d-inline">@csrf
                                <input type="hidden" name="id" value="{{ $g->id }}">
                                <input type="hidden" name="code" value="{{ $g->code }}">
                                <input type="hidden" name="name" value="{{ $g->name }}">
                                <input type="hidden" name="description" value="{{ $g->description }}">
                                <input type="hidden" name="sort_order" value="{{ $g->sort_order }}">
                                <input type="hidden" name="is_active" value="{{ $g->is_active ? 0 : 1 }}">
                                @if($g->is_active)
                                    <button class="btn btn-sm btn-success" data-testid="toggle-grade-{{ $g->code }}"><i class="bi bi-check-circle-fill me-1"></i>Aktif</button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" data-testid="toggle-grade-{{ $g->code }}"><i class="bi bi-slash-circle me-1"></i>Nonaktif</button>
                                @endif
                            </form>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" onclick='editGrade(@json($g))' data-testid="btn-edit-grade-{{ $g->code }}"><i class="bi bi-pencil"></i></button>
                            <a href="{{ route('admin.grades.age-rule', $g) }}" class="btn btn-sm btn-outline-success" data-testid="btn-edit-rule-{{ $g->code }}"><i class="bi bi-sliders"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3 small text-muted">
                <i class="bi bi-info-circle me-1"></i>Grade yang <b>Nonaktif</b> tidak muncul di halaman pendaftaran publik. Klik tombol status untuk toggle cepat.
            </div>
        </div>
    </div>
</div>

<script>
function editGrade(g){
    document.getElementById('g_id').value = g.id;
    document.getElementById('g_code').value = g.code;
    document.getElementById('g_name').value = g.name;
    document.getElementById('g_desc').value = g.description || '';
    document.getElementById('g_sort').value = g.sort_order || 0;
    document.getElementById('g_act').checked = !!g.is_active;
    window.scrollTo({top:0, behavior:'smooth'});
}
function resetForm(){
    document.getElementById('g_id').value = '';
    document.getElementById('g_code').value = '';
    document.getElementById('g_name').value = '';
    document.getElementById('g_desc').value = '';
    document.getElementById('g_sort').value = 0;
    document.getElementById('g_act').checked = true;
}
</script>
@endsection
