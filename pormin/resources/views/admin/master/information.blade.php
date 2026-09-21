@extends('layouts.admin')
@section('title', 'Informasi Landing')
@section('content')
<h1 class="section-title mb-3">Informasi Landing</h1>
<form method="POST" action="{{ route('admin.information.save') }}" class="p-3 bg-white rounded-4 shadow-sm">@csrf
    @php $fields=[
        'landing_hero_title'=>'Judul Hero',
        'landing_hero_subtitle'=>'Subjudul Hero',
        'landing_hero_description'=>'Deskripsi Hero',
        'info_pendaftaran'=>'Informasi Pendaftaran',
        'info_persyaratan'=>'Persyaratan',
        'info_alur'=>'Alur Pendaftaran',
        'info_jadwal'=>'Jadwal',
        'info_faq'=>'FAQ',
        'contact_phone'=>'Telepon Kontak',
        'contact_email'=>'Email Kontak',
        'contact_address'=>'Alamat Kontak',
    ];@endphp
    @foreach($fields as $k=>$l)
        <div class="mb-3"><label class="form-label"><b>{{ $l }}</b></label>
        @if(in_array($k,['landing_hero_title','landing_hero_subtitle','contact_phone','contact_email','contact_address']))
            <input class="form-control" name="{{ $k }}" value="{{ $settings[$k] ?? '' }}">
        @else
            <textarea class="form-control" rows="4" name="{{ $k }}">{{ $settings[$k] ?? '' }}</textarea>
        @endif
        </div>
    @endforeach
    <button class="btn btn-azhar" data-testid="btn-save-info">Simpan</button>
</form>
@endsection
