<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'total' => StudentRegistration::count(),
            'menunggu' => StudentRegistration::where('administrative_status', 'Menunggu Validasi')->count(),
            'lolos_admin' => StudentRegistration::where('administrative_status', 'Lolos Administrasi')->count(),
            'tidak_lolos' => StudentRegistration::where('administrative_status', 'Tidak Lolos Administrasi')->count(),
            'layak' => StudentRegistration::where('observation_eligibility', 'Layak Observasi')->count(),
            'dipanggil' => StudentRegistration::where('status', 'Dipanggil Observasi')->count(),
            'observasi_selesai' => StudentRegistration::where('status', 'Observasi Selesai')->count(),
            'lulus_obs' => StudentRegistration::where('status', 'Lulus Observasi')->count(),
            'tidak_lulus_obs' => StudentRegistration::where('status', 'Tidak Lulus Observasi')->count(),
            'diterima' => StudentRegistration::where('status', 'Diterima')->count(),
        ];

        $perGrade = Grade::query()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($g) {
                $base = StudentRegistration::where('grade_id', $g->id);
                return (object) [
                    'code' => $g->code,
                    'name' => $g->name,
                    'total' => (clone $base)->count(),
                    'lolos_admin' => (clone $base)->where('administrative_status', 'Lolos Administrasi')->count(),
                    'layak' => (clone $base)->where('observation_eligibility', 'Layak Observasi')->count(),
                    'diterima' => (clone $base)->where('status', 'Diterima')->count(),
                ];
            });

        return view('admin.dashboard', compact('counts', 'perGrade'));
    }
}
