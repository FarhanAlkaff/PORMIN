<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\RegistrationSequence;
use Illuminate\Support\Facades\DB;

class RegistrationNumberService
{
    /**
     * Return next registration number for a grade + academic year using a row-locked sequence.
     * MUST be called inside a DB transaction.
     */
    public function next(int $academicYearId, int $gradeId): string
    {
        $grade = Grade::findOrFail($gradeId);

        // Atomic upsert then lock
        $seq = RegistrationSequence::query()
            ->where('academic_year_id', $academicYearId)
            ->where('grade_id', $gradeId)
            ->lockForUpdate()
            ->first();

        if (!$seq) {
            $seq = RegistrationSequence::create([
                'academic_year_id' => $academicYearId,
                'grade_id' => $gradeId,
                'last_sequence' => 0,
            ]);
            // Re-fetch with lock
            $seq = RegistrationSequence::where('id', $seq->id)->lockForUpdate()->first();
        }

        $seq->last_sequence += 1;
        $seq->save();

        return $grade->code . str_pad((string) $seq->last_sequence, 4, '0', STR_PAD_LEFT);
    }
}
