@extends('layouts.admin')
@section('title', 'Informasi Landing & Pengaturan')
@section('content')
<h1 class="section-title mb-3">Informasi Landing & Pengaturan</h1>
<form method="POST" action="{{ route('admin.information.save') }}" enctype="multipart/form-data" class="row g-3">@csrf
    <div class="col-lg-8">
        <div class="p-3 bg-white rounded-4 shadow-sm mb-3">
            <h5 class="section-title mb-3"><i class="bi bi-house-heart me-2" style="color:var(--azhar-gold);"></i>Hero & Konten</h5>
            @php $fields=[
                'landing_hero_title'=>['Judul Hero','input'],
                'landing_hero_subtitle'=>['Subjudul Hero','input'],
                'landing_hero_description'=>['Deskripsi Hero','area'],
                'info_pendaftaran'=>['Informasi Pendaftaran','area'],
                'info_persyaratan'=>['Persyaratan','area'],
                'info_alur'=>['Alur Pendaftaran','area'],
                'info_jadwal'=>['Jadwal','area'],
                'info_faq'=>['FAQ','area'],
            ];@endphp
            @foreach($fields as $k=>$meta)
                <div class="mb-3"><label class="form-label"><b>{{ $meta[0] }}</b></label>
                @if($meta[1]==='input')<input class="form-control" name="{{ $k }}" value="{{ $settings[$k] ?? '' }}">@else
                    <textarea class="form-control" rows="4" name="{{ $k }}">{{ $settings[$k] ?? '' }}</textarea>
                @endif
                </div>
            @endforeach
        </div>

        <div class="p-3 bg-white rounded-4 shadow-sm mb-3">
            <h5 class="section-title mb-3"><i class="bi bi-megaphone me-2" style="color:var(--azhar-gold);"></i>Papan Pengumuman Landing</h5>
            <p class="text-muted small">Muncul sebagai teks berjalan di paling atas halaman landing. Satu baris = satu pengumuman.</p>
            <div class="row g-2">
                <div class="col-md-8"><label class="form-label">Judul Pengumuman</label><input class="form-control" name="announcement_title" value="{{ $settings['announcement_title'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="announcement_active" data-testid="announcement-active"><option value="1" @selected(($settings['announcement_active'] ?? '1')==='1')>Aktif</option><option value="0" @selected(($settings['announcement_active'] ?? '1')==='0')>Nonaktif</option></select></div>
                <div class="col-12"><label class="form-label">Isi Pengumuman (satu baris satu item)</label><textarea class="form-control" rows="4" name="announcement_items" data-testid="announcement-items">{{ $settings['announcement_items'] ?? '' }}</textarea></div>
            </div>
        </div>

        <div class="p-3 bg-white rounded-4 shadow-sm">
            <h5 class="section-title mb-3"><i class="bi bi-whatsapp me-2" style="color:#25d366;"></i>Notifikasi WhatsApp</h5>
            <p class="text-muted small">Sistem otomatis membuat pesan panggilan observasi. Mode <b>link</b> (default) = tombol wa.me — hemat & gratis. Mode <b>fonnte</b> = kirim otomatis via API (butuh token dari fonnte.com).</p>
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label">Kode Negara</label><input class="form-control" name="wa_country_code" value="{{ $settings['wa_country_code'] ?? '62' }}"></div>
                <div class="col-md-4"><label class="form-label">Driver</label>
                    <select class="form-select" name="wa_driver" data-testid="wa-driver">
                        <option value="link" @selected(($settings['wa_driver'] ?? 'link')==='link')>Link (wa.me — manual klik)</option>
                        <option value="fonnte" @selected(($settings['wa_driver'] ?? 'link')==='fonnte')>Fonnte API (otomatis)</option>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Fonnte Token</label><input type="password" class="form-control" name="wa_fonnte_token" placeholder="Isi jika pakai Fonnte" value="{{ $settings['wa_fonnte_token'] ?? '' }}"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="p-3 bg-white rounded-4 shadow-sm mb-3">
            <h5 class="section-title mb-3"><i class="bi bi-image me-2" style="color:var(--azhar-gold);"></i>Logo Sekolah</h5>
            @php $logo = $settings['logo_path'] ?? null; @endphp
            <div class="text-center p-3 mb-3" style="background:var(--azhar-cream);border-radius:12px;">
                @if($logo && file_exists(public_path($logo)))
                    <img src="{{ asset($logo) }}?v={{ time() }}" style="max-width:160px;max-height:160px;object-fit:contain;" data-testid="current-logo">
                @else
                    <div class="text-muted"><i class="bi bi-image display-4"></i><br>Belum ada logo</div>
                @endif
            </div>
            <div class="mb-2"><label class="form-label">Upload Logo Baru</label><input type="file" name="logo" accept="image/*" class="form-control" data-testid="input-logo"><small class="text-muted">PNG/SVG/JPG · max 2MB</small></div>
            @if($logo)<div class="form-check"><input type="checkbox" class="form-check-input" name="logo_remove" value="1" id="rm"><label for="rm" class="form-check-label">Hapus logo saat ini</label></div>@endif
        </div>

        <div class="p-3 bg-white rounded-4 shadow-sm mb-3">
            <h5 class="section-title mb-3"><i class="bi bi-telephone me-2" style="color:var(--azhar-gold);"></i>Kontak</h5>
            <div class="mb-2"><label class="form-label">Telepon</label><input class="form-control" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"></div>
            <div class="mb-2"><label class="form-label">Email</label><input class="form-control" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"></div>
            <div class="mb-2"><label class="form-label">Alamat</label><input class="form-control" name="contact_address" value="{{ $settings['contact_address'] ?? '' }}"></div>
        </div>

        <button class="btn btn-azhar btn-lg w-100" data-testid="btn-save-info"><i class="bi bi-save me-1"></i>Simpan Semua Pengaturan</button>
    </div>
</form>
@endsection
