<?php

namespace VertexSolutions\Academy\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use VertexSolutions\Academy\Models\Certificate;

class CertificateController extends Controller
{
    public function download(Certificate $certificate): Response
    {
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $certificate->load(['user', 'course']);
        $totalMinutes = $certificate->course->lessons()->sum('duration_in_minutes');
        $totalHours = round($totalMinutes / 60, 1);

        $data = [
            'certificate' => $certificate,
            'studentName' => $certificate->user->name,
            'courseTitle' => $certificate->course->title,
            'totalHours' => $totalHours,
            'issuedAt' => $certificate->issued_at->format('d/m/Y'),
            'validationCode' => $certificate->validation_code,
        ];

        $pdf = Pdf::loadView('academy::certificates.pdf', $data)->setPaper('a4', 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="certificado-' . $certificate->id . '.pdf"',
        ]);
    }
}
