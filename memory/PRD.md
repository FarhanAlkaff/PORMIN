# PORMIN (Portal Minat) — Al-Azhar Cairo Palembang

## Original Problem Statement
Bangun aplikasi web PORMIN untuk pendaftaran murid baru online (PG, TK-A, TK-B, SD, SMP, SMA) yang dipakai orang tua murid (OTM) untuk mendaftarkan calon murid, kemudian diverifikasi admin, dipanggil observasi, dan diputuskan diterima/ditolak. Fitur inti: validasi usia (per 1 Juli tahun ajaran) + duplicate detection + nomor pendaftaran otomatis + PDF + QR + admin workflow.

## Tech Stack
- Laravel 13.31 + PHP 8.3
- MariaDB 10.11 (data persistent di `/app/pormin-data`)
- Blade + Bootstrap 5 + jQuery/AJAX
- barryvdh/laravel-dompdf, simplesoftwareio/simple-qrcode
- Supervisor mengelola `pormin` (php artisan serve :3000) & `mariadb`

## Struktur Utama
- App: `/app/pormin/` (Laravel root)
- Data MySQL persistent: `/app/pormin-data/`
- Bootstrap ulang setelah pod restart: `bash /app/scripts/setup.sh`
- Preview URL: https://pormin-registration-1.preview.emergentagent.com/

## Fitur Selesai (Phase 1-8 ✅)
### Phase 1 — Laravel + DB + Auth ✅
- Laravel 13 fresh install, MariaDB 10.11
- Auth session (`SESSION_DRIVER=database`), admin login dengan throttle 5/menit
### Phase 2 — Master Data ✅
- Tabel: `academic_years`, `grades`, `grade_age_rules`, `campuses`, `student_categories`, `information_settings`
- Seeder lengkap untuk semua master (2026/2027 aktif, 6 grade, 1 kampus, 2 kategori)
### Phase 3 — Landing Page ✅
- Hero + tombol Daftar/Cek + kartu 6 grade + info persyaratan/alur/jadwal (dinamis dari DB)
- Menu: Beranda, Informasi, Persyaratan, Alur, Jadwal, FAQ, Cek Pendaftaran, Login Admin
### Phase 4 — Form Pendaftaran ✅
- Layout 2 kolom: data anak + kontak/orang tua + data pendaftaran
- Modal Konfirmasi sebelum submit, checkbox pernyataan
- Semua field sesuai screenshot referensi (Nama Lengkap, Panggilan, JK, NISN, Kelas, Tempat/Tgl Lahir, Status Keluarga, Anak Ke-, Alamat, HP, Asal Sekolah, Ayah/Ibu/Kakek, Pekerjaan, HP orang tua)
### Phase 5 — Validasi Usia ✅
- `AgeValidationService` menghitung usia pada 1 Juli tahun ajaran (Carbon)
- Validasi realtime AJAX (`/xhr/validate-age`) + revalidation di backend saat submit
- Aturan usia dari `grade_age_rules` (configurable oleh admin, termasuk SMA)
- **Test 1-6 PASS**: TK-A 4 tahun tepat, TK-A 3y11m29d, TK-B 5th, SD 6th, SMP 15th, SMP >15
### Phase 6 — Duplicate Detection ✅
- Normalisasi nama (lowercase + trim + collapse whitespace) → `normalized_name`
- Unique index composite: `(normalized_name, birth_date, grade_id, academic_year_id)`
- Detection realtime AJAX (`/xhr/check-duplicate`) + DB constraint sebagai lapisan akhir
- **Test 7-10 PASS**: dupe exact, nama sama beda tgl, grade beda, tahun beda
### Phase 7 — Registration Number ✅
- `RegistrationNumberService` pakai `registration_sequences` + `lockForUpdate` (aman concurrent)
- Format: `PG0001`, `TK-A0001`, `SD0001`, dst
- Reset per (grade, academic_year); unique composite `(registration_number, academic_year_id)`
- **Test 11 PASS**: concurrent aman
### Phase 8 — PDF + QR Code ✅
- Bukti Pendaftaran PDF (`Bukti-Pendaftaran-{NUMBER}.pdf`) dengan QR SVG
- Surat Panggilan Observasi PDF (`Surat-Observasi-{NUMBER}.pdf`)
- Halaman verifikasi publik `/verify/{token}` menampilkan data terbatas (tidak sensitif)

## Fitur Tambahan (Phase 9-10 ✅ Diselesaikan Awal)
### Phase 9 — Admin Dashboard ✅
- KPI: Total, Menunggu, Lolos Admin, Layak Observasi, Dipanggil, Diterima
- Statistik per Grade (Pendaftar / Lolos Admin / Layak / Diterima)
### Phase 10 — Admin Validation ✅
- Detail pendaftaran → form validasi (status admin + kelayakan observasi + catatan)
- Auto derive main status
### Phase 11 — Observation Workflow ✅
- Form jadwal (tanggal, jam, lokasi, ruang, catatan) → status "Dipanggil Observasi"
- Form hasil observasi + keputusan → status "Diterima"/"Ditolak"/"Lulus Observasi"
### Phase 12 — Status History ✅
- `registration_status_histories` mencatat setiap perubahan otomatis
- Timeline muncul di halaman hasil publik & detail admin
- `registration_notes` catatan admin per jenis
### Phase 13 — Export ✅
- CSV export dari filter `/admin/registrations/export`
- Filter admin: tahun, grade, kampus, status; search: no/nama/NISN/HP/ortu

## Input Data Lama ✅
- `/admin/registrations/create` — admin bisa input pendaftar dari sistem lama/offline
- Tetap lewat semua validasi (usia, dupe, nomor otomatis)

## Business Rules Terimplementasi (RULE 1-20)
- RULE 1: Satu OTM ⇒ banyak anak (father_phone/mother_phone tidak unique) ✅
- RULE 4-5: Duplicate check (nama+tgl+grade+tahun) + normalisasi ✅
- RULE 6-7: Usia dihitung 1 Juli, validasi front+back ✅
- RULE 8: DB unique constraint composite ✅
- RULE 9-11: Nomor otomatis, sequence per grade+tahun, aman concurrent (lockForUpdate) ✅
- RULE 12-16: Validasi admin, kelayakan observasi, history status, jadwal & hasil observasi ✅
- RULE 17-20: OTM lihat status, download bukti PDF, surat observasi PDF, QR code ✅

## Kredensial Admin
- Email: `admin@alazharcairoplg.sch.id`
- Password: `Admin@12345`
- URL Login: `/admin/login`

## Cara Menjalankan
```bash
# Setelah pod restart (jika PHP/MariaDB hilang):
bash /app/scripts/setup.sh

# Manual restart:
sudo supervisorctl restart pormin mariadb
```
DB user: `pormin` / `pormin_password` @ socket `/var/run/mysqld/mysqld.sock` / db `pormin`

## Endpoints Utama
### Public
- `GET /` — Landing
- `GET /pendaftaran` — Form
- `POST /pendaftaran` — Submit (throttle 10/menit)
- `POST /xhr/validate-age` — AJAX validasi usia
- `POST /xhr/check-duplicate` — AJAX cek dupe
- `GET/POST /pendaftaran/cek` — Cek pendaftaran (nomor + tgl lahir)
- `GET /pendaftaran/hasil/{number}` — Halaman hasil
- `GET /pendaftaran/pdf/{number}` — Download PDF bukti
- `GET /pendaftaran/surat-observasi/{number}` — Download PDF surat panggilan
- `GET /verify/{token}` — Verifikasi QR (data terbatas)

### Admin (auth)
- `/admin/dashboard`, `/admin/registrations`, `/admin/registrations/create`, `/admin/registrations/{id}`
- `/admin/registrations/{id}/validate|observation|observation-result`
- `/admin/registrations/export` (CSV)
- Master: `/admin/{academic-years|grades|campuses|categories|information}`
- `/admin/grades/{id}/age-rule` — kelola aturan usia per grade (termasuk SMA)

## Backlog (Phase 14+)
- Fonnte WhatsApp API auto-send (siap plug-and-play, admin tinggal isi token di Informasi → Notifikasi WhatsApp)
- Rich CSV/Excel export dengan phpspreadsheet
- Feature tests otomatis (Pest/PHPUnit) untuk 12 scenarios
- Rate limit granular per endpoint dan IP-based
- Multi-admin roles (super admin, operator observasi) via Policy/Gate
- Rich text editor untuk konten landing page

## Fitur Tambahan (Iterasi 2 ✅ 21 Sep 2026)
### Logo Upload
- Admin → Informasi Landing → "Logo Sekolah": upload PNG/SVG/JPG max 2MB
- Disimpan di `/app/pormin/public/uploads/`, path di `information_settings.logo_path`
- Tampil di navbar public, sidebar admin, header PDF bukti & surat observasi
- Fallback ke brand mark "AZ" jika logo belum diupload

### Batch Import CSV
- `/admin/registrations/import` — upload file CSV (max 5MB)
- `BatchRegistrationImporter` service: parse row-by-row lewat `RegistrationService`
- Setiap baris melewati validasi usia + deteksi duplikat + generate nomor otomatis
- Download template lewat `/admin/registrations/import/template`
- Report akhir: sukses/gagal + daftar error per baris

### Announcement Board
- Marquee/teks berjalan di paling atas landing (gradient green-gold, animated CSS)
- Admin → Informasi Landing → "Papan Pengumuman": judul + item baris demi baris + toggle aktif/nonaktif
- Data di `information_settings` (`announcement_title`, `announcement_items`, `announcement_active`)

### WhatsApp Notification (Modular)
- `WhatsappNotificationService` dengan 2 driver:
  - **link** (default, gratis): generate wa.me URL dengan pesan pre-formatted, tombol "Buka WhatsApp & Kirim" untuk admin
  - **fonnte**: kirim otomatis via Fonnte API (butuh token dari fonnte.com — konfigurasi di Admin → Informasi → Notifikasi WhatsApp)
- Nomor HP otomatis dinormalisasi (0 → 62, +62 → 62)
- Pesan mencakup: nomor pendaftaran, nama, jenjang, TA, tanggal + jam + lokasi + ruang observasi
- Tercatat sebagai `registration_notes` dengan `note_type='Notifikasi WA'`
- Auto-trigger saat admin simpan jadwal observasi + tombol "Kirim Ulang WA"

## Persistence Notes
- MySQL data → `/app/pormin-data/` (survives pod restart)
- Setup script → `/app/scripts/setup.sh` (idempotent, install PHP/MariaDB + supervisor config + migrate)
- Uploads → `/app/pormin/public/uploads/` (di dalam /app)

## Verified Test Results (End-to-End)
✅ Landing render OK  
✅ Form submit → `TK-A0001` created, status "Terdaftar"  
✅ Dupe (`AHMAD FARHAN` uppercase) terdeteksi tanpa nomor baru  
✅ Admin login OK  
✅ Detail pendaftaran, Validasi → "Layak Observasi", Jadwal → "Dipanggil Observasi", Hasil → "Diterima"  
✅ Timeline status muncul lengkap  
✅ PDF bukti download OK (application/pdf, 883KB, PDF-1.7 valid)  
✅ QR SVG embed di PDF, verify page publik OK  
✅ Age service: 6/6 Test 1-6 PASS  
✅ Duplicate service: 4/4 Test 7-10 PASS  
✅ Sequence reset per tahun: TK-A 2027 mulai TK-A0001 lagi
