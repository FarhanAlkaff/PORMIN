<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Grade;
use App\Models\GradeAgeRule;
use App\Models\InformationSetting;
use App\Models\StudentCategory;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function years() { return view('admin.master.years', ['items' => AcademicYear::orderByDesc('start_year')->get()]); }
    public function saveYear(Request $req)
    {
        $data = $req->validate([
            'id' => ['nullable', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:20'],
            'start_year' => ['required', 'integer', 'min:2020', 'max:2099'],
            'end_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if (!empty($data['is_active'])) AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::updateOrCreate(['id' => $data['id'] ?? 0], [
            'name' => $data['name'], 'start_year' => $data['start_year'],
            'end_year' => $data['end_year'], 'is_active' => (bool)($data['is_active'] ?? false),
        ]);
        return back()->with('success', 'Tahun ajaran tersimpan.');
    }

    public function grades()
    {
        return view('admin.master.grades', ['items' => Grade::with('ageRule')->orderBy('sort_order')->get()]);
    }
    public function saveGrade(Request $req)
    {
        $data = $req->validate([
            'id' => ['nullable', 'exists:grades,id'],
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        Grade::updateOrCreate(['id' => $data['id'] ?? 0], [
            'code' => $data['code'], 'name' => $data['name'], 'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0, 'is_active' => (bool)($data['is_active'] ?? false),
        ]);
        return back()->with('success', 'Grade tersimpan.');
    }

    public function ageRule(Grade $grade)
    {
        $rule = $grade->ageRule ?? new GradeAgeRule(['reference_month' => 7, 'reference_day' => 1]);
        return view('admin.master.age_rule', compact('grade', 'rule'));
    }
    public function saveAgeRule(Request $req, Grade $grade)
    {
        $data = $req->validate([
            'minimum_age_years' => ['nullable', 'integer', 'min:0', 'max:30'],
            'minimum_age_months' => ['nullable', 'integer', 'min:0', 'max:12'],
            'minimum_age_days' => ['nullable', 'integer', 'min:0', 'max:31'],
            'maximum_age_years' => ['nullable', 'integer', 'min:0', 'max:30'],
            'maximum_age_months' => ['nullable', 'integer', 'min:0', 'max:12'],
            'maximum_age_days' => ['nullable', 'integer', 'min:0', 'max:31'],
            'reference_month' => ['required', 'integer', 'min:1', 'max:12'],
            'reference_day' => ['required', 'integer', 'min:1', 'max:31'],
        ]);
        GradeAgeRule::updateOrCreate(['grade_id' => $grade->id], $data + ['is_active' => true]);
        return redirect()->route('admin.grades')->with('success', 'Aturan usia tersimpan.');
    }

    public function campuses() { return view('admin.master.campuses', ['items' => Campus::all()]); }
    public function saveCampus(Request $req)
    {
        $data = $req->validate([
            'id' => ['nullable', 'exists:campuses,id'],
            'name' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        Campus::updateOrCreate(['id' => $data['id'] ?? 0], [
            'name' => $data['name'], 'address' => $data['address'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);
        return back()->with('success', 'Kampus tersimpan.');
    }

    public function categories() { return view('admin.master.categories', ['items' => StudentCategory::all()]); }
    public function saveCategory(Request $req)
    {
        $data = $req->validate([
            'id' => ['nullable', 'exists:student_categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        StudentCategory::updateOrCreate(['id' => $data['id'] ?? 0], [
            'name' => $data['name'], 'is_active' => (bool)($data['is_active'] ?? false),
        ]);
        return back()->with('success', 'Kategori tersimpan.');
    }

    public function information()
    {
        $settings = InformationSetting::pluck('value', 'key');
        return view('admin.master.information', compact('settings'));
    }
    public function saveInformation(Request $req)
    {
        $keys = ['landing_hero_title', 'landing_hero_subtitle', 'landing_hero_description', 'info_pendaftaran', 'info_persyaratan', 'info_alur', 'info_jadwal', 'info_faq', 'contact_phone', 'contact_email', 'contact_address'];
        foreach ($keys as $k) {
            if ($req->has($k)) InformationSetting::set($k, (string) $req->input($k));
        }
        return back()->with('success', 'Informasi landing tersimpan.');
    }
}
