<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfService
{
    /**
     * Generate a PDF from a Blade view and return it as a download response.
     *
     * @param  string  $view  View name (e.g. 'bible::memberpanel.plans.pdf')
     * @param  array  $data  Data to pass to the view
     * @param  string  $filename  Download filename
     * @param  string  $paper  Paper size (e.g. 'A4')
     * @param  string  $orientation  'portrait' or 'landscape'
     * @param  array<int>  $margins  [top, right, bottom, left] in mm (optional; can be applied in view/CSS)
     * @return Response|StreamedResponse
     */
    public function downloadView(
        string $view,
        array $data = [],
        string $filename = 'document.pdf',
        string $paper = 'A4',
        string $orientation = 'portrait',
        array $margins = [15, 15, 15, 15]
    ): Response {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper($paper, strtolower($orientation));

        return $pdf->download($filename);
    }
}
