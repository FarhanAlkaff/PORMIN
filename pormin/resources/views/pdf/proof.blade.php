<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Bukti Pendaftaran {{ $reg->registration_number }}</title>
<style>
    body{font-family:DejaVu Sans, sans-serif;font-size:11px;color:#1a2a22;}
    .header{border-bottom:3px double #0f5f3a;padding-bottom:8px;margin-bottom:14px;}
    .header h1{font-size:16px;margin:0;color:#0f5f3a;letter-spacing:.05em;}
    .header .sub{font-size:12px;color:#555;}
    h2{font-size:13px;color:#0f5f3a;border-bottom:1px solid #c9a24b;padding-bottom:3px;margin:14px 0 8px;}
    table{width:100%;border-collapse:collapse;margin-bottom:8px;}
    table td{padding:4px 6px;vertical-align:top;}
    .lbl{color:#555;width:38%;}
    .box{background:#faf6ec;padding:8px;border-radius:6px;margin-bottom:10px;}
    .qr{float:right;text-align:center;}
    .footer{position:fixed;bottom:20px;left:0;right:0;text-align:center;font-size:9px;color:#888;}
    .status{background:#0f5f3a;color:#fff;padding:3px 10px;border-radius:10px;font-size:10px;display:inline-block;}
</style></head>
<body>
    <div class="header">
        <div class="qr"><img src="data:image/svg+xml;base64,{{ $qr }}" width="90"><div style="font-size:8px;">Scan untuk verifikasi</div></div>
        <h1>AL-AZHAR CAIRO PALEMBANG</h1>
        <div class="sub">BUKTI PENDAFTARAN MURID BARU · PORMIN (Portal Minat)</div>
    </div>

    <div class="box">
        <table>
            <tr><td class="lbl">Nomor Pendaftaran</td><td><b>{{ $reg->registration_number }}</b></td>
                <td class="lbl">Tahun Ajaran</td><td><b>{{ $reg->academicYear->name }}</b></td></tr>
            <tr><td class="lbl">Tanggal Pendaftaran</td><td>{{ $reg->registered_at?->translatedFormat('d F Y H:i') }}</td>
                <td class="lbl">Grade</td><td><b>{{ $reg->grade->code }}</b> — {{ $reg->grade->name }}</td></tr>
            <tr><td class="lbl">Kampus</td><td>{{ $reg->campus->name }}</td>
                <td class="lbl">Kategori</td><td>{{ $reg->category->name }}</td></tr>
            <tr><td class="lbl">Status</td><td colspan="3"><span class="status">{{ $reg->status }}</span></td></tr>
        </table>
    </div>

    <h2>Data Calon Murid</h2>
    <table>
        <tr><td class="lbl">Nama Lengkap</td><td>{{ $reg->full_name }}</td><td class="lbl">Nama Panggilan</td><td>{{ $reg->nickname }}</td></tr>
        <tr><td class="lbl">Jenis Kelamin</td><td>{{ $reg->gender === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td><td class="lbl">NISN</td><td>{{ $reg->nisn ?: '-' }}</td></tr>
        <tr><td class="lbl">Tempat, Tgl Lahir</td><td>{{ $reg->birth_place }}, {{ \Carbon\Carbon::parse($reg->birth_date)->translatedFormat('d F Y') }}</td>
            <td class="lbl">Usia pada {{ \Carbon\Carbon::parse($reg->age_reference_date)->translatedFormat('d F Y') }}</td>
            <td>{{ $reg->age_years }} tahun {{ $reg->age_months }} bulan {{ $reg->age_days }} hari</td></tr>
        <tr><td class="lbl">Status Keluarga</td><td>{{ $reg->family_status }}</td><td class="lbl">Anak Ke-</td><td>{{ $reg->child_order }}</td></tr>
        <tr><td class="lbl">Alamat</td><td colspan="3">{{ $reg->address }}</td></tr>
        <tr><td class="lbl">Asal Sekolah</td><td>{{ $reg->previous_school ?: '-' }}</td><td class="lbl">No. HP</td><td>{{ $reg->phone }}</td></tr>
    </table>

    <h2>Data Orang Tua / Wali</h2>
    <table>
        <tr><td class="lbl">Ayah</td><td>{{ $reg->father_name }}</td><td class="lbl">Pekerjaan Ayah</td><td>{{ $reg->father_job ?: '-' }}</td></tr>
        <tr><td class="lbl">Ibu</td><td>{{ $reg->mother_name }}</td><td class="lbl">Pekerjaan Ibu</td><td>{{ $reg->mother_job ?: '-' }}</td></tr>
        <tr><td class="lbl">Kakek Dari Ayah</td><td colspan="3">{{ $reg->grandfather_name }}</td></tr>
        <tr><td class="lbl">HP Ayah</td><td>{{ $reg->father_phone ?: '-' }}</td><td class="lbl">HP Ibu</td><td>{{ $reg->mother_phone ?: '-' }}</td></tr>
    </table>

    <div class="footer">Dokumen ini dihasilkan sistem PORMIN · {{ now()->translatedFormat('d F Y H:i') }} · Verifikasi: {{ url('/verify/'.$reg->verification_token) }}</div>
</body></html>
