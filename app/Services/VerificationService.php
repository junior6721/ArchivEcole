<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class VerificationService
{
    public function searchDiploma(
        string $diplomaNumber,
        string $studentName,
        string $graduationYear,
        ?string $institutionName = null
    ): ?Diploma {
        $query = Diploma::where('diploma_number', $diplomaNumber)
            ->where('graduation_date', 'like', $graduationYear . '%');

        if ($institutionName) {
            $query->whereHas('institution', function ($q) use ($institutionName) {
                $q->where('name', 'like', '%' . $institutionName . '%');
            });
        }

        $diploma = $query->first();

        // Verify student name if diploma found
        if ($diploma && !$this->matchesStudentName($diploma, $studentName)) {
            return null;
        }

        return $diploma;
    }

    protected function matchesStudentName(Diploma $diploma, string $studentName): bool
    {
        $fullName = $diploma->student->getFullNameAttribute();
        
        // Simple fuzzy matching for names
        $similarity = 0;
        similar_text(
            strtolower($fullName),
            strtolower($studentName),
            $similarity
        );

        return $similarity >= 70;
    }

    public function createVerificationRequest(
        array $data,
        ?Diploma $diploma = null
    ): VerificationRequest {
        return VerificationRequest::create([
            'diploma_id' => $diploma?->id,
            'institution_id' => $diploma?->institution_id,
            'verifier_name' => $data['verifier_name'] ?? null,
            'verifier_email' => $data['verifier_email'] ?? null,
            'verifier_phone' => $data['verifier_phone'] ?? null,
            'diploma_number' => $data['diploma_number'],
            'student_name' => $data['student_name'],
            'graduation_year' => $data['graduation_year'],
            'status' => 'pending',
            'result' => 'pending',
        ]);
    }

    public function completeVerification(
        VerificationRequest $request,
        bool $isAuthentic = true,
        ?string $notes = null
    ): VerificationRequest {
        $request->update([
            'status' => 'completed',
            'result' => $isAuthentic ? 'authentic' : 'not_found',
            'verification_date' => now(),
            'verified_by_id' => auth()->id(),
            'notes' => $notes,
        ]);

        return $request->refresh();
    }
}
