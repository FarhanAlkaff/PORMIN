<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Grade;
use App\Models\RegistrationNote;
use App\Models\RegistrationStatusHistory;
use App\Models\StudentCategory;
use App\Models\StudentRegistration;
use App\Services\AuditLogger;
use App\Services\PdfService;
use App\Services\RegistrationService;
use App\Http\Requests\StoreRegistrationRequest;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct(private RegistrationService $registrationService, private PdfService $pdfService) {}

    public function index(Request $request)
    {
        $q = StudentRegistration::query()->with(['grade', 'academicYear', 'campus']);

        if ($v = $request->query('academic_year_id')) $q->where('academic_year_id', $v);
        if ($v = $request->query('grade_id')) $q->where('grade_id', $v);
        if ($v = $request->query('campus_id')) $q->where('campus_id', $v);
        if ($v = $request->query('status')) $q->where('status', $v);
        if ($v = $request->query('administrative_status')) $q->where('administrative_status', $v);
        if ($v = $request->query('observation_eligibility')) $q->where('observation_eligibility', $v);
        if ($v = $request->query('search')) {
            $q->where(function ($qq) use ($v) {
                $qq->where('registration_number', 'like', "%{$v}%")
                    ->orWhere('full_name', 'like', "%{$v}%")
                    ->orWhere('nisn', 'like', "%{$v}%")
                    ->orWhere('father_name', 'like', "%{$v}%")
                    ->orWhere('mother_name', 'like', "%{$v}%")
                    ->orWhere('phone', 'like', "%{$v}%");
            });
        }

        $registrations = $q->latest('id')->paginate(15)->withQueryString();
        $filters = [
            'years' => AcademicYear::orderByDesc('start_year')->get(),
            'grades' => Grade::orderBy('sort_order')->get(),
            'campuses' => Campus::orderBy('name')->get(),
            'statuses' => ['Terdaftar', 'Menunggu Validasi', 'Lolos Administrasi', 'Tidak Lolos Administrasi', 'Layak Observasi', 'Dipanggil Observasi', 'Observasi Selesai', 'Lulus Observasi', 'Tidak Lulus Observasi', 'Diterima', 'Ditolak'],
        ];

        return view('admin.registrations.index', compact('registrations', 'filters'));
    }

    public function create()
    {
        return view('admin.registrations.create', [
            'grades' => Grade::with('ageRule')->where('is_active', true)->orderBy('sort_order')->get(),
            'years' => AcademicYear::orderByDesc('start_year')->get(),
            'campuses' => Campus::where('is_active', true)->get(),
            'categories' => StudentCategory::where('is_active', true)->get(),
        ]);
    }

    public function store(StoreRegistrationRequest $request)
    {
        try {
            $result = $this->registrationService->register($request->validated(), auth()->id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['age' => $e->getMessage()])->withInput();
        }
        $reg = $result['registration'];
        AuditLogger::log("Admin membuat pendaftaran {$reg->registration_number}", $reg);

        if ($result['status'] === 'duplicate') {
            return redirect()->route('admin.registrations.show', $reg->id)->with('info', 'Data sudah ada — menampilkan data existing.');
        }
        return redirect()->route('admin.registrations.show', $reg->id)->with('success', 'Pendaftaran berhasil ditambahkan.');
    }

    public function show(StudentRegistration $registration)
    {
        $registration->load(['academicYear', 'grade.ageRule', 'campus', 'category', 'histories.changer', 'notes.admin']);
        return view('admin.registrations.show', ['reg' => $registration]);
    }

    public function validateAdmin(Request $request, StudentRegistration $registration)
    {
        $data = $request->validate([
            'administrative_status' => ['required', 'in:Menunggu Validasi,Lolos Administrasi,Tidak Lolos Administrasi'],
            'observation_eligibility' => ['required', 'in:Belum Ditentukan,Layak Observasi,Tidak Layak Observasi'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $registration->status;
        $registration->administrative_status = $data['administrative_status'];
        $registration->observation_eligibility = $data['observation_eligibility'];

        // Derive main status
        if ($data['administrative_status'] === 'Tidak Lolos Administrasi') {
            $registration->status = 'Tidak Lolos Administrasi';
        } elseif ($data['observation_eligibility'] === 'Tidak Layak Observasi') {
            $registration->status = 'Tidak Layak Observasi';
        } elseif ($data['observation_eligibility'] === 'Layak Observasi') {
            $registration->status = 'Layak Observasi';
        } elseif ($data['administrative_status'] === 'Lolos Administrasi') {
            $registration->status = 'Lolos Administrasi';
        } else {
            $registration->status = 'Menunggu Validasi';
        }
        $registration->save();

        if ($oldStatus !== $registration->status) {
            RegistrationStatusHistory::create([
                'registration_id' => $registration->id,
                'old_status' => $oldStatus,
                'new_status' => $registration->status,
                'changed_by' => auth()->id(),
                'note' => $data['note'] ?? null,
                'created_at' => now(),
            ]);
        }

        if (!empty($data['note'])) {
            RegistrationNote::create([
                'registration_id' => $registration->id,
                'admin_id' => auth()->id(),
                'note' => $data['note'],
                'note_type' => 'Validasi Administrasi',
            ]);
        }

        AuditLogger::log("Update validasi {$registration->registration_number} → {$registration->status}", $registration);
        return back()->with('success', 'Validasi berhasil disimpan.');
    }

    public function observation(Request $request, StudentRegistration $registration)
    {
        $data = $request->validate([
            'observation_date' => ['required', 'date'],
            'observation_time' => ['required'],
            'observation_location' => ['required', 'string', 'max:191'],
            'observation_room' => ['nullable', 'string', 'max:100'],
            'observation_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $oldStatus = $registration->status;
        $registration->fill($data);
        $registration->status = 'Dipanggil Observasi';
        $registration->save();

        RegistrationStatusHistory::create([
            'registration_id' => $registration->id,
            'old_status' => $oldStatus,
            'new_status' => 'Dipanggil Observasi',
            'changed_by' => auth()->id(),
            'note' => 'Jadwal observasi ditetapkan',
            'created_at' => now(),
        ]);
        AuditLogger::log("Jadwal observasi {$registration->registration_number}", $registration, $data);
        return back()->with('success', 'Jadwal observasi berhasil disimpan.');
    }

    public function observationResult(Request $request, StudentRegistration $registration)
    {
        $data = $request->validate([
            'observation_result' => ['required', 'in:Lulus,Tidak Lulus'],
            'decision' => ['required', 'in:Belum,Diterima,Ditolak'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
        $oldStatus = $registration->status;
        $registration->observation_result = $data['observation_result'];

        if ($data['observation_result'] === 'Lulus') {
            $registration->status = $data['decision'] === 'Diterima' ? 'Diterima'
                : ($data['decision'] === 'Ditolak' ? 'Ditolak' : 'Lulus Observasi');
        } else {
            $registration->status = $data['decision'] === 'Ditolak' ? 'Ditolak' : 'Tidak Lulus Observasi';
        }
        $registration->save();

        RegistrationStatusHistory::create([
            'registration_id' => $registration->id,
            'old_status' => $oldStatus,
            'new_status' => $registration->status,
            'changed_by' => auth()->id(),
            'note' => $data['note'] ?? null,
            'created_at' => now(),
        ]);
        if (!empty($data['note'])) {
            RegistrationNote::create([
                'registration_id' => $registration->id,
                'admin_id' => auth()->id(),
                'note' => $data['note'],
                'note_type' => 'Observasi',
            ]);
        }
        AuditLogger::log("Hasil observasi {$registration->registration_number} → {$registration->status}", $registration);
        return back()->with('success', 'Hasil observasi disimpan.');
    }

    public function destroy(StudentRegistration $registration)
    {
        AuditLogger::log("Hapus pendaftaran {$registration->registration_number}", $registration);
        $registration->delete();
        return redirect()->route('admin.registrations.index')->with('success', 'Data dihapus.');
    }

    public function export(Request $request)
    {
        $q = StudentRegistration::query()->with(['grade', 'academicYear', 'campus']);
        if ($v = $request->query('academic_year_id')) $q->where('academic_year_id', $v);
        if ($v = $request->query('grade_id')) $q->where('grade_id', $v);
        if ($v = $request->query('status')) $q->where('status', $v);

        $rows = $q->orderBy('registration_number')->get();
        $filename = 'export_pendaftaran_' . now()->format('YmdHis') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No', 'No. Pendaftaran', 'Nama Anak', 'Grade', 'Tahun', 'Kampus', 'Tgl Lahir', 'Nama Ayah', 'Nama Ibu', 'HP', 'Status']);
            foreach ($rows as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    $r->registration_number, $r->full_name,
                    $r->grade->code ?? '', $r->academicYear->name ?? '',
                    $r->campus->name ?? '', $r->birth_date?->format('d-m-Y'),
                    $r->father_name, $r->mother_name, $r->phone, $r->status,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
